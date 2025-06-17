@extends('layouts.app')

@section('title', 'Our Delicious Cookies - CookieTime')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Our Delicious Cookie Kits
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Handcrafted themed cookie collections baked fresh daily. Build your perfect cookie box!
            </p>
        </div>

        <!-- Cookie Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
            @foreach($cookies as $cookie)
                <div class="cookie-card bg-white rounded-xl shadow-lg overflow-hidden"
                     x-data="cookieCard({{ $cookie['id'] }})">

                    <!-- Cookie Image -->
                    <div class="relative h-64 bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center overflow-hidden">
                        @if(isset($cookie['image']) && !empty($cookie['image']))
                            <img src="{{ $cookie['image'] }}"
                                 alt="{{ $cookie['name'] }}"
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="text-8xl hidden">🍪</div>
                        @else
                            <div class="text-8xl">🍪</div>
                        @endif

                        <div class="absolute top-4 right-4 bg-amber-500 text-white px-3 py-2 rounded-lg text-sm font-semibold shadow-lg">
                            ${{ number_format($cookie['price'], 2) }}
                        </div>

                        <!-- Kit Badge for themed collections -->
                        <div class="absolute top-4 left-4 bg-white/90 text-amber-700 px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                            Cookie Kit
                        </div>
                    </div>

                    <!-- Cookie Info -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $cookie['name'] }}</h3>
                        <p class="text-gray-600 mb-4 text-sm line-clamp-3">{{ $cookie['description'] }}</p>

                        <!-- Quantity Controls -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <button @click="decreaseQuantity()"
                                        class="quantity-btn w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-bold transition-all duration-200">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>

                                <span class="text-lg font-semibold text-gray-800 w-8 text-center" x-text="quantity"></span>

                                <button @click="increaseQuantity()"
                                        class="quantity-btn w-8 h-8 bg-amber-500 hover:bg-amber-600 rounded-full flex items-center justify-center text-white font-bold transition-all duration-200">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>

                            <button @click="openModal()"
                                    class="text-amber-600 hover:text-amber-700 font-medium text-sm transition-colors">
                                <i class="fas fa-info-circle mr-1"></i>
                                More Info
                            </button>
                        </div>

                        <!-- Add to Box Button -->
                        <div x-show="quantity > 0"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="mt-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                        <span class="text-green-700 font-medium">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span x-text="quantity"></span> kit<span x-show="quantity > 1">s</span> added to your box!
                        </span>
                            </div>
                        </div>
                    </div>

                    <!-- Info Modal -->
                    <div x-show="showModal"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
                         @click="closeModalOnBackdrop($event)"
                         style="z-index: 9999;">

                        <div class="bg-white rounded-xl max-w-md w-full overflow-hidden shadow-2xl"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.stop>

                            <!-- Modal Image -->
                            <div class="h-48 bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center overflow-hidden">
                                @if(isset($cookie['image']) && !empty($cookie['image']))
                                    <img src="{{ $cookie['image'] }}"
                                         alt="{{ $cookie['name'] }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="text-6xl hidden">🍪</div>
                                @else
                                    <div class="text-6xl">🍪</div>
                                @endif
                            </div>

                            <!-- Modal Content -->
                            <div class="p-6">
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $cookie['name'] }}</h3>
                                <div class="text-amber-600 font-bold text-xl mb-4">${{ number_format($cookie['price'], 2) }}</div>

                                <p class="text-gray-600 mb-6">{{ $cookie['description'] }}</p>

                                <div class="flex space-x-3">
                                    <button @click="closeModal()"
                                            type="button"
                                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Close
                                    </button>
                                    <button @click="addToBoxAndClose()"
                                            type="button"
                                            class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Add to Box
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Bottom Bar -->
        <div x-show="$store.cart.totalItems > 0"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="sticky-bottom bg-white border-t shadow-lg">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-box text-amber-600 text-xl mr-3"></i>
                        <div>
                            <div class="font-semibold text-gray-800">
                                <span x-text="$store.cart.totalItems"></span> Kit<span x-show="$store.cart.totalItems > 1">s</span> in Your Box
                            </div>
                            <div class="text-sm text-gray-600">Ready to checkout?</div>
                        </div>
                    </div>

                    <a href="{{ route('cart.index') }}"
                       class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center shadow-lg">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        View My Box
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cookieCard(cookieId) {
            return {
                cookieId: cookieId,
                quantity: 0,
                showModal: false,

                init() {
                    // Get initial quantity from store
                    const cart = Alpine.store('cart').items;
                    this.quantity = cart[cookieId] || 0;

                    // Watch for cart changes
                    this.$watch('$store.cart.items', () => {
                        const cart = Alpine.store('cart').items;
                        this.quantity = cart[cookieId] || 0;
                    });
                },

                increaseQuantity() {
                    this.quantity++;
                    Alpine.store('cart').updateItem(this.cookieId, this.quantity);
                },

                decreaseQuantity() {
                    if (this.quantity > 0) {
                        this.quantity--;
                        Alpine.store('cart').updateItem(this.cookieId, this.quantity);
                    }
                },

                openModal() {
                    this.showModal = true;
                    // Prevent body scrolling when modal is open
                    document.body.style.overflow = 'hidden';
                },

                closeModal() {
                    this.showModal = false;
                    // Restore body scrolling
                    document.body.style.overflow = '';
                },

                addToBoxAndClose() {
                    this.increaseQuantity();
                    this.closeModal();
                },

                closeModalOnBackdrop(event) {
                    // Only close if clicking the backdrop, not the modal content
                    if (event.target === event.currentTarget) {
                        this.closeModal();
                    }
                }
            }
        }

        // Global escape key handler for modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                // Find any open modals and close them
                const modalElements = document.querySelectorAll('[x-data*="cookieCard"]');
                modalElements.forEach(element => {
                    const alpineData = Alpine.$data(element);
                    if (alpineData && alpineData.showModal) {
                        alpineData.closeModal();
                    }
                });
            }
        });
    </script>

    <style>
        /* Line clamp utility for descriptions */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Enhanced cookie card animations */
        .cookie-card {
            transition: all 0.3s ease;
        }

        .cookie-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        /* Smooth quantity button animations */
        .quantity-btn:hover {
            transform: scale(1.1);
        }

        .quantity-btn:active {
            transform: scale(0.95);
        }

        /* Image loading animation */
        img {
            transition: opacity 0.3s ease;
        }

        /* Modal improvements */
        .fixed {
            position: fixed !important;
        }

        /* Ensure modal appears above everything */
        [x-show="showModal"] {
            z-index: 9999 !important;
        }

        /* Prevent text selection on buttons */
        button {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
@endsection
