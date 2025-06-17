<?php

namespace App\Http\Controllers;

use App\Models\Cookie;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function create(Request $request)
    {
        Log::info('Checkout create method called');

        $cart = session('cart', []);

        if (empty($cart)) {
            Log::warning('Checkout attempted with empty cart');
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Calculate cart totals
        $cartItems = [];
        $totalAmount = 0;
        $totalItems = 0;

        foreach ($cart as $id => $quantity) {
            $cookie = Cookie::find($id);
            if ($cookie) {
                $subtotal = $cookie['price'] * $quantity;
                $cartItems[] = array_merge($cookie, [
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ]);
                $totalAmount += $subtotal;
                $totalItems += $quantity;
            }
        }

        Log::info('Cart totals calculated', [
            'total_amount' => $totalAmount,
            'total_items' => $totalItems,
            'cart_items_count' => count($cartItems)
        ]);

        // Create order record
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'total_items' => $totalItems,
            'customer_email' => '', // Will be filled after payment
            'customer_name' => '',
            'shipping_address' => [],
            'items' => $cartItems
        ]);

        Log::info('Order created', ['order_id' => $order->id, 'order_number' => $order->order_number]);

        // Create order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'cookie_id' => $item['id'],
                'cookie_name' => $item['name'],
                'cookie_description' => $item['description'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal']
            ]);
        }

        // Create Stripe line items
        $lineItems = [];
        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['name'],
                        'description' => $item['description'],
                    ],
                    'unit_amount' => $item['price'] * 100, // Stripe uses cents
                ],
                'quantity' => $item['quantity'],
            ];
        }

        try {
            $successUrl = route('checkout.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}';
            $cancelUrl = route('checkout.cancelled', ['order' => $order->id]);

            Log::info('Creating Stripe session with URLs', [
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl
            ]);

            // Create Stripe Checkout Session
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'customer_email' => null,
                'shipping_address_collection' => [
                    'allowed_countries' => ['US', 'CA'],
                ],
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]
            ]);

            // Update order with Stripe session ID
            $order->update(['stripe_session_id' => $checkoutSession->id]);

            Log::info('Stripe session created successfully', [
                'session_id' => $checkoutSession->id,
                'checkout_url' => $checkoutSession->url
            ]);

            return redirect($checkoutSession->url);

        } catch (\Exception $e) {
            Log::error('Stripe session creation failed', [
                'error' => $e->getMessage(),
                'order_id' => $order->id
            ]);

            // Delete the order if Stripe session creation fails
            $order->delete();

            return redirect()->route('cart.index')->with('error', 'Payment processing failed. Please try again.');
        }
    }

    public function success(Request $request, Order $order)
    {
        Log::info('Success method called', [
            'order_id' => $order->id,
            'session_id' => $request->get('session_id'),
            'request_params' => $request->all()
        ]);

        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            Log::error('No session_id in success callback', [
                'order_id' => $order->id,
                'url' => $request->fullUrl()
            ]);
            return redirect()->route('cart.index')->with('error', 'Invalid payment session - no session ID');
        }

        if ($order->stripe_session_id !== $sessionId) {
            Log::error('Session ID mismatch', [
                'order_id' => $order->id,
                'expected' => $order->stripe_session_id,
                'received' => $sessionId
            ]);
            return redirect()->route('cart.index')->with('error', 'Invalid payment session - ID mismatch');
        }

        try {
            // Retrieve the session from Stripe
            Log::info('Retrieving Stripe session', ['session_id' => $sessionId]);
            $session = Session::retrieve($sessionId);

            Log::info('Stripe session retrieved', [
                'payment_status' => $session->payment_status,
                'customer_email' => $session->customer_details->email ?? 'not_provided',
                'customer_name' => $session->customer_details->name ?? 'not_provided'
            ]);

            if ($session->payment_status === 'paid') {
                // Handle missing customer name (common with Apple Pay, Google Pay, etc.)
                $customerName = $session->customer_details->name;
                if (empty($customerName)) {
                    // Extract name from email or use a default
                    $emailParts = explode('@', $session->customer_details->email);
                    $customerName = ucfirst($emailParts[0]) ?: 'Valued Customer';

                    Log::info('Customer name was null, using fallback', [
                        'original_name' => $session->customer_details->name,
                        'fallback_name' => $customerName,
                        'payment_method_used' => 'likely_apple_pay_or_google_pay'
                    ]);
                }

                // Update order with customer info and mark as paid
                $order->update([
                    'status' => 'paid',
                    'customer_email' => $session->customer_details->email,
                    'customer_name' => $customerName, // Now guaranteed to not be null
                    'shipping_address' => $session->shipping_details ? $session->shipping_details->address : [],
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'paid_at' => now()
                ]);

                Log::info('Order updated to paid status', [
                    'order_id' => $order->id,
                    'customer_name_used' => $customerName
                ]);

                // Clear the cart
                session()->forget('cart');
                Log::info('Cart cleared from session');

                // Send confirmation email
                try {
                    Mail::to($order->customer_email)->send(new OrderConfirmation($order));
                    Log::info('Confirmation email sent', ['email' => $order->customer_email]);
                } catch (\Exception $e) {
                    Log::error('Failed to send order confirmation email', [
                        'error' => $e->getMessage(),
                        'order_id' => $order->id
                    ]);
                }

                Log::info('Redirecting to success view', ['order_id' => $order->id]);
                return view('checkout.success', compact('order'));
            } else {
                Log::warning('Payment not completed', [
                    'payment_status' => $session->payment_status,
                    'order_id' => $order->id
                ]);
                return redirect()->route('cart.index')->with('error', 'Payment was not completed');
            }
        } catch (\Exception $e) {
            Log::error('Stripe session retrieval failed', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId,
                'order_id' => $order->id
            ]);
            return redirect()->route('cart.index')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    public function cancelled(Order $order)
    {
        Log::info('Cancelled method called', ['order_id' => $order->id]);

        // Mark order as cancelled
        $order->update(['status' => 'cancelled']);

        Log::info('Order marked as cancelled', ['order_id' => $order->id]);

        return view('checkout.cancelled', compact('order'));
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('stripe-signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        Log::info('Webhook received', ['has_signature' => !empty($sigHeader)]);

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Webhook invalid payload', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Webhook invalid signature', ['error' => $e->getMessage()]);
            return response('Invalid signature', 400);
        }

        Log::info('Webhook event processed', ['event_type' => $event->type]);

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                Log::info('Processing checkout.session.completed', ['session_id' => $session->id]);

                // Find the order
                $order = Order::where('stripe_session_id', $session->id)->first();

                if ($order && $order->status === 'pending') {
                    $order->update([
                        'status' => 'paid',
                        'customer_email' => $session->customer_details->email,
                        'customer_name' => $session->customer_details->name,
                        'shipping_address' => $session->shipping_details ? $session->shipping_details->address : [],
                        'stripe_payment_intent_id' => $session->payment_intent,
                        'paid_at' => now()
                    ]);

                    Log::info('Order updated via webhook', ['order_id' => $order->id]);

                    // Send confirmation email
                    try {
                        Mail::to($order->customer_email)->send(new OrderConfirmation($order));
                        Log::info('Confirmation email sent via webhook', ['email' => $order->customer_email]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send webhook confirmation email', [
                            'error' => $e->getMessage(),
                            'order_id' => $order->id
                        ]);
                    }
                } else {
                    Log::warning('Order not found or already processed for webhook', [
                        'session_id' => $session->id,
                        'order_found' => $order ? 'yes' : 'no',
                        'order_status' => $order ? $order->status : 'n/a'
                    ]);
                }
                break;

            case 'payment_intent.succeeded':
                Log::info('Payment intent succeeded', ['payment_intent' => $event->data->object->id]);
                break;

            default:
                Log::info('Unhandled webhook event type', ['event_type' => $event->type]);
        }

        return response('Webhook handled', 200);
    }
}
