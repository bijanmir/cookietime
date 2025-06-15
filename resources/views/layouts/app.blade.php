<!DOCTYPE html>
<html lang="en" x-data>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CookieTime - Premium Handcrafted Cookies')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .sticky-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 50;
        }

        .cookie-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .cookie-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .quantity-btn {
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            transform: scale(1.1);
        }

        .cart-badge {
            animation: pulse 0.5s ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="bg-gray-50" x-data="appData()">
<!-- Navigation -->
<nav class="bg-white shadow-lg sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('cookies.index') }}" class="flex items-center">
                    <i class="fas fa-cookie-bite text-3xl text-amber-600 mr-2"></i>
                    <span class="text-2xl font-bold text-gray-800">CookieTime</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('cookies.index') }}" class="text-gray-700 hover:text-amber-600 px-3 py-2 rounded-md font-medium transition-colors">
                    Our Cookies
                </a>
                <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-amber-600 px-3 py-2 rounded-md font-medium transition-colors">
                    My Box
                </a>

                <!-- Cart Badge -->
                <div class="relative">
                    <a href="{{ route('cart.index') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Cart
                    </a>
                    <div id="cart-badge"
                         x-show="$store.cart.totalItems > 0"
                         x-text="$store.cart.totalItems"
                         class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center cart-badge">
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-amber-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="md:hidden bg-white border-t">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('cookies.index') }}" class="block px-3 py-2 text-gray-700 hover:text-amber-600 font-medium">
                Our Cookies
            </a>
            <a href="{{ route('cart.index') }}" class="block px-3 py-2 text-gray-700 hover:text-amber-600 font-medium">
                My Box
            </a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="pb-20">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-cookie-bite text-3xl text-amber-400 mr-2"></i>
                <span class="text-2xl font-bold">CookieTime</span>
            </div>
            <p class="text-gray-400">Handcrafted cookies made with love since 2024</p>
            <div class="mt-4 flex justify-center space-x-6">
                <a href="#" class="text-gray-400 hover:text-amber-400"><i class="fab fa-facebook text-xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-amber-400"><i class="fab fa-instagram text-xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-amber-400"><i class="fab fa-twitter text-xl"></i></a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Alpine.js Data
    function appData() {
        return {
            mobileMenuOpen: false,

            init() {
                // Initialize cart store
                this.syncCartFromStorage();

                // Listen for cart updates
                this.$watch('$store.cart.totalItems', () => {
                    this.updateCartBadge();
                });

                // Listen for custom cart events
                document.addEventListener('cart:updated', () => {
                    this.syncCartFromStorage();
                });

                document.addEventListener('cart:sync', () => {
                    this.syncCartFromStorage();
                });
            },

            syncCartFromStorage() {
                const cart = JSON.parse(localStorage.getItem('cookieCart') || '{}');
                const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);

                Alpine.store('cart').totalItems = totalItems;
                Alpine.store('cart').items = cart;
            },

            updateCartBadge() {
                const badge = document.getElementById('cart-badge');
                if (badge) {
                    badge.classList.add('cart-badge');
                    setTimeout(() => {
                        badge.classList.remove('cart-badge');
                    }, 500);
                }
            }
        }
    }

    // Alpine.js Cart Store
    document.addEventListener('alpine:init', () => {
        Alpine.store('cart', {
            items: JSON.parse(localStorage.getItem('cookieCart') || '{}'),
            totalItems: 0,

            init() {
                this.totalItems = Object.values(this.items).reduce((sum, qty) => sum + qty, 0);
            },

            updateItem(id, quantity) {
                if (quantity > 0) {
                    this.items[id] = quantity;
                } else {
                    delete this.items[id];
                }

                this.totalItems = Object.values(this.items).reduce((sum, qty) => sum + qty, 0);
                localStorage.setItem('cookieCart', JSON.stringify(this.items));

                // Sync with backend
                fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ id: id, quantity: quantity })
                });

                // Dispatch update event
                document.dispatchEvent(new CustomEvent('cart:updated'));
            }
        });
    });

    // HTMX Configuration
    document.addEventListener('DOMContentLoaded', function() {
        // Configure HTMX
        htmx.config.requestClass = 'htmx-request';
        htmx.config.indicatorClass = 'htmx-indicator';
    });
</script>

</body>
</html>
