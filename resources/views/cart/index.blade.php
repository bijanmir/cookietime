@extends('layouts.app')

@section('title', 'My Cookie Box - CookieTime')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-box text-amber-600 mr-3"></i>
                My Cookie Box
            </h1>
            <p class="text-xl text-gray-600">
                Review your delicious selection
            </p>
        </div>

        <!-- Cart Items Container -->
        <div id="cart-items-container">
            @include('cart._items', ['cartItems' => $cartItems])
        </div>

        <!-- Empty Cart Message -->
        <div x-show="$store.cart.totalItems === 0" class="text-center py-12">
            <div class="text-6xl mb-4">🍪</div>
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">Your cookie box is empty</h3>
            <p class="text-gray-600 mb-8">Start building your perfect cookie collection!</p>
            <a href="{{ route('cookies.index') }}"
               class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors inline-flex items-center">
                <i class="fas fa-cookie-bite mr-2"></i>
                Browse Our Cookies
            </a>
        </div>
    </div>

    <script>
        // HTMX Response Handler for Cart Updates
        document.addEventListener('htmx:afterRequest', function(event) {
            if (event.detail.target.id === 'cart-items-container') {
                const response = JSON.parse(event.detail.xhr.responseText);

                if (response.success) {
                    // Update the cart items HTML
                    document.getElementById('cart-items-container').innerHTML = response.html;

                    // Update localStorage
                    localStorage.setItem('cookieCart', JSON.stringify(response.cart));

                    // Update Alpine store
                    Alpine.store('cart').items = response.cart;
                    Alpine.store('cart').totalItems = response.totalItems;

                    // Update cart badge
                    if (response.badge) {
                        document.getElementById('cart-badge').outerHTML = response.badge;
                    }

                    // Dispatch sync event
                    document.dispatchEvent(new CustomEvent('cart:sync'));
                }
            }
        });
    </script>
@endsection
