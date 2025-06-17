@extends('layouts.app')

@section('title', 'Order Complete - CookieTime')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                <i class="fas fa-check text-3xl text-green-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Order Complete!</h1>
            <p class="text-xl text-gray-600">Thank you for your order, {{ $order->customer_name }}!</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <!-- Order Details -->
            <div class="bg-green-50 border-b border-green-100 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-1">Order {{ $order->order_number }}</h2>
                        <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>
                        Payment Confirmed
                    </span>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Cookie Box</h3>

                @foreach($order->items as $item)
                    <div class="flex items-center space-x-4 py-4 border-b border-gray-100 last:border-b-0">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <div class="text-2xl">🍪</div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $item['name'] }}</h4>
                            <p class="text-sm text-gray-600">{{ $item['description'] }}</p>
                            <p class="text-sm text-amber-600 font-medium">${{ number_format($item['price'], 2) }} each</p>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-gray-800">{{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}</div>
                            <div class="text-lg font-bold text-amber-600">${{ number_format($item['subtotal'], 2) }}</div>
                        </div>
                    </div>
                @endforeach

                <!-- Total -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-6">
                    <div>
                        <div class="text-lg font-semibold text-gray-800">Total</div>
                        <div class="text-sm text-gray-600">{{ $order->total_items }} cookies</div>
                    </div>
                    <div class="text-2xl font-bold text-gray-800">${{ number_format($order->total_amount, 2) }}</div>
                </div>
            </div>

            <!-- Shipping Address -->
            @if($order->shipping_address)
                <div class="bg-gray-50 p-6 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Shipping Address</h3>
                    <div class="text-gray-600">
                        <p>{{ $order->customer_name }}</p>
                        <p>{{ $order->shipping_address['line1'] ?? '' }}</p>
                        @if(isset($order->shipping_address['line2']) && $order->shipping_address['line2'])
                            <p>{{ $order->shipping_address['line2'] }}</p>
                        @endif
                        <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                        <p>{{ $order->shipping_address['country'] ?? '' }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-blue-800 mb-3">What's Next?</h3>
            <ul class="space-y-2 text-blue-700">
                <li class="flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    You'll receive an order confirmation email at {{ $order->customer_email }}
                </li>
                <li class="flex items-center">
                    <i class="fas fa-cookie-bite mr-2"></i>
                    We'll start baking your fresh cookies right away
                </li>
                <li class="flex items-center">
                    <i class="fas fa-shipping-fast mr-2"></i>
                    Your order will ship within 1-2 business days
                </li>
                <li class="flex items-center">
                    <i class="fas fa-truck mr-2"></i>
                    Estimated delivery: 3-5 business days
                </li>
            </ul>
        </div>

        <!-- Actions -->
        <div class="text-center">
            <a href="{{ route('cookies.index') }}"
               class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors inline-flex items-center">
                <i class="fas fa-cookie-bite mr-2"></i>
                Order More Cookies
            </a>
        </div>
    </div>
@endsection
