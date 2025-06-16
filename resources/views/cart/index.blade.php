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
        <div id="cart-items-container" hx-swap="innerHTML" hx-swap-oob="true">
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
        // Listen for cart update events from HTMX
        document.addEventListener('cartUpdated', function(event) {
            const data = event.detail;

            console.log('Cart updated via HTMX:', data);

            // Update localStorage
            localStorage.setItem('cookieCart', JSON.stringify(data.cart));

            // Update Alpine store
            if (Alpine.store('cart')) {
                Alpine.store('cart').items = data.cart;
                Alpine.store('cart').totalItems = data.totalItems;
            }

            // Update cart badge
            const badge = document.getElementById('cart-badge');
            if (badge) {
                if (data.totalItems > 0) {
                    badge.textContent = data.totalItems;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }

            // Dispatch sync event for other components
            document.dispatchEvent(new CustomEvent('cart:sync'));
        });

        // Enhanced HTMX error handling
        document.addEventListener('htmx:responseError', function(event) {
            console.error('HTMX Response Error:', event.detail);

            // Show user-friendly error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50';
            errorDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span>Failed to update cart. Please try again.</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(errorDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (errorDiv.parentElement) {
                    errorDiv.remove();
                }
            }, 5000);
        });

        // Debug HTMX requests
        document.addEventListener('htmx:beforeRequest', function(event) {
            console.log('HTMX Request:', {
                method: event.detail.requestConfig.verb,
                url: event.detail.requestConfig.path,
                data: event.detail.requestConfig.parameters
            });
        });

        // Log when content is swapped
        document.addEventListener('htmx:afterSwap', function(event) {
            console.log('HTMX content swapped:', event.detail.target);

            // Re-process HTMX attributes on new content
            htmx.process(event.detail.target);
        });

        // Ensure HTMX processes new content
        document.addEventListener('htmx:afterSettle', function(event) {
            console.log('HTMX settled:', event.detail.target);
        });
    </script>
@endsection
