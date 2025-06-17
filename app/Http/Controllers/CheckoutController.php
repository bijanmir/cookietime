<?php

namespace App\Http\Controllers;

use App\Models\Cookie;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $cart = session('cart', []);

        if (empty($cart)) {
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
                        'images' => [], // Add actual image URLs here if available
                    ],
                    'unit_amount' => $item['price'] * 100, // Stripe uses cents
                ],
                'quantity' => $item['quantity'],
            ];
        }

        try {
            // Create Stripe Checkout Session
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancelled', ['order' => $order->id]),
                'customer_email' => null, // Let customer enter their email
                'shipping_address_collection' => [
                    'allowed_countries' => ['US', 'CA'], // Adjust as needed
                ],
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ]
            ]);

            // Update order with Stripe session ID
            $order->update(['stripe_session_id' => $checkoutSession->id]);

            return redirect($checkoutSession->url);

        } catch (\Exception $e) {
            // Delete the order if Stripe session creation fails
            $order->delete();

            return redirect()->route('cart.index')->with('error', 'Payment processing failed. Please try again.');
        }
    }

    public function success(Request $request, Order $order)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId || $order->stripe_session_id !== $sessionId) {
            return redirect()->route('cart.index')->with('error', 'Invalid payment session');
        }

        try {
            // Retrieve the session from Stripe
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                // Update order with customer info and mark as paid
                $order->update([
                    'status' => 'paid',
                    'customer_email' => $session->customer_details->email,
                    'customer_name' => $session->customer_details->name,
                    'shipping_address' => $session->shipping_details ? $session->shipping_details->address : [],
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'paid_at' => now()
                ]);

                // Clear the cart
                session()->forget('cart');

                // Send confirmation email
                try {
                    Mail::to($order->customer_email)->send(new OrderConfirmation($order));
                } catch (\Exception $e) {
                    // Log email error but don't fail the order
                    \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
                }

                return view('checkout.success', compact('order'));
            }
        } catch (\Exception $e) {
            \Log::error('Stripe session retrieval failed: ' . $e->getMessage());
        }

        return redirect()->route('cart.index')->with('error', 'Payment verification failed');
    }

    public function cancelled(Order $order)
    {
        // Optionally delete the order or mark as cancelled
        $order->update(['status' => 'cancelled']);

        return view('checkout.cancelled', compact('order'));
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('stripe-signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

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

                    // Send confirmation email
                    try {
                        Mail::to($order->customer_email)->send(new OrderConfirmation($order));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
                    }
                }
                break;

            case 'payment_intent.succeeded':
                // Additional handling if needed
                break;

            default:
                \Log::info('Received unknown event type ' . $event->type);
        }

        return response('Webhook handled', 200);
    }
}
