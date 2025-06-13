@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-12 text-white">
        <h1 class="text-4xl font-bold text-amber-400 mb-8">Your Cart 🛒</h1>

        {{-- Cart Items --}}
        <div class="grid gap-6">
            @foreach ($cartItems as $item)
                <div class="flex flex-col md:flex-row items-center bg-zinc-800 rounded-xl shadow-lg p-4 md:p-6">
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-lg mb-4 md:mb-0 md:mr-6">
                    <div class="flex-1 text-left space-y-1">
                        <h3 class="text-xl font-semibold">{{ $item['name'] }}</h3>
                        <p class="text-zinc-400">${{ number_format($item['price'], 2) }}</p>
                        <div class="flex items-center space-x-2 mt-2">
                            <button class="px-2 py-1 bg-zinc-700 hover:bg-zinc-600 rounded">−</button>
                            <span>{{ $item['qty'] }}</span>
                            <button class="px-2 py-1 bg-zinc-700 hover:bg-zinc-600 rounded">+</button>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 md:ml-6">
                        <button class="text-red-500 hover:text-red-400">Remove</button>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Cart Summary --}}
        <div class="mt-10 bg-zinc-900 p-6 rounded-xl text-right shadow-lg">
            <p class="text-xl font-semibold text-zinc-300">Subtotal: ${{ number_format($subtotal, 2) }}</p>
            <a href="/checkout" class="inline-block mt-4 bg-amber-400 text-black font-bold py-3 px-6 rounded-full hover:bg-amber-300 transition">
                Proceed to Checkout
            </a>
        </div>
    </div>
@endsection
