<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\CartController;


// This route already exists and shows your welcome page.
Route::get('/', function () {
    return view('welcome');
});

// ADD THIS NEW ROUTE for your HTMX button
Route::get('/cookie-of-the-day', [CookieController::class, 'showCookieOfTheDay']);

// Add this route to show all cookies
Route::get('/cookies', [CookieController::class, 'index'])->name('cookies.index'); 

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');