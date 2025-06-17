{{-- resources/views/checkout/cancelled.blade.php --}}
@extends('layouts.app')

@section('title', 'Payment Cancelled - CookieTime')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-4">
                <i class="fas fa-exclamation-triangle text-3xl text-yellow-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Payment Cancelled</h1>
            <p class="text-xl text-gray-600">Your payment was cancelled. Your cart has been saved for you.</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <p class="text-gray-600 mb-6">Don't worry! Your cookies are still waiting for you in your cart.</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('cart.index') }}"
                   class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Return to Cart
                </a>
                <a href="{{ route('cookies.index') }}"
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-cookie-bite mr-2"></i>
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
@endsection
