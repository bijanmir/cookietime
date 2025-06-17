<?php

use App\Http\Controllers\CookieController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    return redirect()->route('cookies.index');
});

Route::get('/cookies', [CookieController::class, 'index'])->name('cookies.index');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/partial', [CartController::class, 'partial'])->name('cart.partial');
Route::get('/cart/badge', [CartController::class, 'badge'])->name('cart.badge');
Route::match(['get', 'post'], '/cart/sync', [CartController::class, 'sync'])->name('cart.sync');


Route::post('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancelled/{order}', [CheckoutController::class, 'cancelled'])->name('checkout.cancelled');
Route::post('/stripe/webhook', [CheckoutController::class, 'webhook'])->name('stripe.webhook');

Route::get('/test-stripe', function() {
    try {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $account = \Stripe\Account::retrieve();
        return "Stripe connection successful! Account ID: " . $account->id;
    } catch (Exception $e) {
        return "Stripe error: " . $e->getMessage();
    }
});

Route::get('/test-mail', function() {
    try {
        Mail::raw('Test email from CookieTime', function($message) {
            $message->to('test@example.com')->subject('Test Email');
        });
        return "Mail sent successfully!";
    } catch (Exception $e) {
        return "Mail error: " . $e->getMessage();
    }
});

Route::get('/test-success/{order}', function(App\Models\Order $order) {
    return view('checkout.success', compact('order'));
});

Route::get('/test-cancelled/{order}', function(App\Models\Order $order) {
    return view('checkout.cancelled', compact('order'));
});


Route::get('/debug-urls/{order}', function(App\Models\Order $order) {
    $successUrl = route('checkout.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}';
    $cancelUrl = route('checkout.cancelled', ['order' => $order->id]);

    return response()->json([
        'success_url' => $successUrl,
        'cancel_url' => $cancelUrl,
        'order_id' => $order->id
    ]);
});
