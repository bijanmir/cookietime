{{-- resources/views/cart/_items.blade.php --}}
<div x-show="$store.cart.totalItems > 0">
    @if(count($cartItems) > 0)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            @php $total = 0; @endphp

            @foreach($cartItems as $item)
                @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp

                <div class="border-b border-gray-100 last:border-b-0 p-6">
                    <div class="flex items-center space-x-6">
                        <!-- Cookie Image -->
                        <div class="w-20 h-20 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <div class="text-3xl">🍪</div>
                        </div>

                        <!-- Cookie Info -->
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $item['name'] }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $item['description'] }}</p>
                            <div class="text-amber-600 font-semibold">${{ number_format($item['price'], 2) }} each</div>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3">
                                <!-- Decrease Button -->
                                <form style="display: inline;"
                                      hx-post="{{ route('cart.update') }}"
                                      hx-target="#cart-items-container"
                                      hx-swap="innerHTML"
                                      hx-indicator=".htmx-indicator">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                    <input type="hidden" name="quantity" value="{{ max(0, $item['quantity'] - 1) }}">
                                    <button type="submit"
                                            class="w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-bold transition-colors disabled:opacity-50"
                                        {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-minus text-xs"></i>
                                        <span class="htmx-indicator hidden">
                                            <i class="fas fa-spinner fa-spin text-xs"></i>
                                        </span>
                                    </button>
                                </form>

                                <span class="text-lg font-semibold text-gray-800 w-8 text-center">{{ $item['quantity'] }}</span>

                                <!-- Increase Button -->
                                <form style="display: inline;"
                                      hx-post="{{ route('cart.update') }}"
                                      hx-target="#cart-items-container"
                                      hx-swap="innerHTML"
                                      hx-indicator=".htmx-indicator">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                    <button type="submit"
                                            class="w-8 h-8 bg-amber-500 hover:bg-amber-600 rounded-full flex items-center justify-center text-white font-bold transition-colors">
                                        <i class="fas fa-plus text-xs"></i>
                                        <span class="htmx-indicator hidden">
                                            <i class="fas fa-spinner fa-spin text-xs"></i>
                                        </span>
                                    </button>
                                </form>
                            </div>

                            <!-- Subtotal -->
                            <div class="text-right">
                                <div class="text-lg font-semibold text-gray-800">${{ number_format($subtotal, 2) }}</div>
                                <div class="text-sm text-gray-600">{{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}</div>
                            </div>

                            <!-- Remove Button -->
                            <form style="display: inline;"
                                  hx-post="{{ route('cart.remove') }}"
                                  hx-target="#cart-items-container"
                                  hx-swap="innerHTML"
                                  hx-confirm="Are you sure you want to remove this cookie from your box?"
                                  hx-indicator=".htmx-indicator">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item['id'] }}">
                                <button type="submit"
                                        class="text-red-500 hover:text-red-700 p-2 transition-colors"
                                        title="Remove from box">
                                    <i class="fas fa-trash text-lg"></i>
                                    <span class="htmx-indicator hidden">
                                        <i class="fas fa-spinner fa-spin text-lg"></i>
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Total Section -->
            <div class="bg-gray-50 p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-lg font-semibold text-gray-800">Total</div>
                        <div class="text-sm text-gray-600">{{ array_sum(array_column($cartItems, 'quantity')) }} cookies</div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-gray-800">${{ number_format($total, 2) }}</div>
                    </div>
                </div>

                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('cookies.index') }}"
                       class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg text-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Continue Shopping
                    </a>
                    <button class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                        <i class="fas fa-credit-card mr-2"></i>
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Empty cart state when all items are removed --}}
        <div class="text-center py-12">
            <div class="text-6xl mb-4">🍪</div>
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">Your cookie box is empty</h3>
            <p class="text-gray-600 mb-8">Start building your perfect cookie collection!</p>
            <a href="{{ route('cookies.index') }}"
               class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors inline-flex items-center">
                <i class="fas fa-cookie-bite mr-2"></i>
                Browse Our Cookies
            </a>
        </div>
    @endif
</div>
