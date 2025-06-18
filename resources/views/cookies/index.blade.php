@extends('layouts.app')

@section('title', 'Our Delicious Cookies - CookieTime')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="cookieStore()">

        <!-- Conditional Rendering: Show Modal Details OR Cookie Grid -->

        <!-- Modal Detail View (replaces the grid when viewing details) -->
        <div x-show="viewingDetails"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="fixed inset-0 bg-white z-50 overflow-y-auto"
             @click.away="closeDetails()"
             @keydown.escape="closeDetails()">

            <template x-if="selectedCookie">
                <div class="w-full h-full">
                    <div class="bg-white h-full flex flex-col"
                         @click.stop>
                        <!-- Back Button -->
                        <div class="p-4 border-b">
                            <button @click="closeDetails()"
                                    class="flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Cookie Kits
                            </button>
                        </div>

                        <!-- Cookie Detail Image -->
                        <div class="h-[25rem] bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center overflow-hidden">
                            <template x-if="selectedCookie.image">
                                <img :src="selectedCookie.image"
                                     :alt="selectedCookie.name"
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            </template>
                            <div class="text-8xl" x-show="!selectedCookie.image">🍪</div>
                        </div>

                        <!-- Cookie Detail Info -->
                        <div class="p-8">
                            <div class="flex items-start justify-between mb-4">
                                <h2 class="text-3xl font-bold text-gray-800" x-text="selectedCookie.name"></h2>
                                <div class="bg-amber-500 text-white px-4 py-2 rounded-lg text-xl font-semibold shadow-lg">
                                    $<span x-text="selectedCookie.price ? selectedCookie.price.toFixed(2) : '0.00'"></span>
                                </div>
                            </div>

                            <p class="text-gray-600 mb-8 text-lg leading-relaxed" x-text="selectedCookie.description"></p>

                            <!-- Quantity Controls -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center space-x-4">
                                    <button @click="decreaseQuantity(selectedCookie.id)"
                                            class="quantity-btn w-12 h-12 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-bold transition-all duration-200">
                                        <i class="fas fa-minus"></i>
                                    </button>

                                    <span class="text-2xl font-semibold text-gray-800 w-12 text-center"
                                          x-text="getQuantity(selectedCookie.id)"></span>

                                    <button @click="increaseQuantity(selectedCookie.id)"
                                            class="quantity-btn w-12 h-12 bg-amber-500 hover:bg-amber-600 rounded-full flex items-center justify-center text-white font-bold transition-all duration-200">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                <button @click="addToBoxAndClose(selectedCookie.id)"
                                        class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center shadow-lg">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add to Box
                                </button>
                            </div>

                            <!-- Added Confirmation -->
                            <div x-show="getQuantity(selectedCookie.id) > 0"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <span class="text-green-700 font-medium">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span x-text="getQuantity(selectedCookie.id)"></span>
                                kit<span x-show="getQuantity(selectedCookie.id) > 1">s</span> added to your box!
                            </span>
                            </div>
                        </div>
                    </div>
            </template>
        </div>

        <!-- Cookie Grid (hidden when viewing details) -->
        <div x-show="!viewingDetails"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="mb-8 px-5">

            <!-- Header (only shown in grid view) -->
            <div class="text-center my-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                    Our Delicious Cookie Kits
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Handcrafted themed cookie collections baked fresh daily. Build your perfect cookie box!
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @foreach($cookies as $cookie)
                    <div class="cookie-card bg-white rounded-xl shadow-lg overflow-hidden"
                         x-data="{ cookieId: {{ $cookie['id'] }} }">

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
                                    <button @click="decreaseQuantity(cookieId)"
                                            class="quantity-btn w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-bold transition-all duration-200">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>

                                    <span class="text-lg font-semibold text-gray-800 w-8 text-center"
                                          x-text="getQuantity(cookieId)"></span>

                                    <button @click="increaseQuantity(cookieId)"
                                            class="quantity-btn w-8 h-8 bg-amber-500 hover:bg-amber-600 rounded-full flex items-center justify-center text-white font-bold transition-all duration-200">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>

                                <button @click="openDetails({{ $cookie['id'] }})"
                                        class="text-amber-600 hover:text-amber-700 font-medium text-sm transition-colors">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    More Info
                                </button>
                            </div>

                            <!-- Add to Box Button -->
                            <div x-show="getQuantity(cookieId) > 0"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="mt-4">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                                <span class="text-green-700 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <span x-text="getQuantity(cookieId)"></span>
                                    kit<span x-show="getQuantity(cookieId) > 1">s</span> added to your box!
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Sticky Bottom Bar (shown only in grid view) -->
        <div x-show="$store.cart.totalItems > 0"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="sticky-bottom bg-white border-t shadow-lg z-50">

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
        function cookieStore() {
            return {
                viewingDetails: false,
                selectedCookie: null,
                cookies: @json($cookies),

                init() {
                    // Watch for escape key
                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape' && this.viewingDetails) {
                            this.closeDetails();
                        }
                    });
                },

                openDetails(cookieId) {
                    this.selectedCookie = this.cookies.find(cookie => cookie.id == cookieId);
                    this.viewingDetails = true;
                    // Prevent body scrolling
                    document.body.style.overflow = 'hidden';
                },

                closeDetails() {
                    this.viewingDetails = false;
                    this.selectedCookie = null;
                    // Restore body scrolling
                    document.body.style.overflow = '';
                },

                increaseQuantity(cookieId) {
                    const currentQuantity = this.getQuantity(cookieId);
                    Alpine.store('cart').updateItem(cookieId, currentQuantity + 1);
                },

                decreaseQuantity(cookieId) {
                    const currentQuantity = this.getQuantity(cookieId);
                    if (currentQuantity > 0) {
                        Alpine.store('cart').updateItem(cookieId, currentQuantity - 1);
                    }
                },

                getQuantity(cookieId) {
                    const cart = Alpine.store('cart').items;
                    return cart[cookieId] || 0;
                },

                addToBoxAndClose(cookieId) {
                    this.increaseQuantity(cookieId);
                    this.closeDetails();
                }
            }
        }
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

        /* Prevent text selection on buttons */
        button {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        /* Sticky bottom positioning */
        .sticky-bottom {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 45;
        }
    </style>
@endsection
