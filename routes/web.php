<?php

use App\Http\Controllers\CookieController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

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
