@extends('layouts.app')

@section('content')

    <div class="relative bg-orange-100 py-20 overflow-hidden rounded-xl shadow-lg mb-12">
        <div class="container mx-auto text-center relative z-10">
            <h1 class="text-5xl md:text-6xl font-bold text-orange-600 mb-6" style="font-family: 'Figtree', sans-serif;">A Sweet Selection Just For You</h1>
            <p class="text-xl md:text-2xl text-gray-700 italic mb-10">Indulge in our freshly baked, handcrafted cookies.</p>
        </div>
        <div class="absolute inset-0 bg-gradient-to-br from-orange-200 to-yellow-100 opacity-50 rounded-xl"></div>
    </div>

    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 xl:gap-12">
            @foreach ($cookies as $cookie)
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-xl">
                <div class="relative h-56 md:h-64 overflow-hidden">
                    {{-- Using a dynamic placeholder image service --}}
                    <img src="https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRiTdBQZqeyfGviqZOidT_wnYedWbFgd1NyCYxucz3q5IBHuG1iocQEoMsgK4FdF7pXP1bBfc5S6ZWwftdEgFvmXd21DKx4c2TMTfspxA" alt="A delicious {{ $cookie['name'] }} cookie" class="object-cover w-full h-full rounded-t-lg transition-transform duration-300 transform scale-100 hover:scale-105" loading="lazy">
                    <div class="absolute top-2 right-2 bg-yellow-300 text-yellow-800 py-1 px-3 rounded-full text-xs font-bold shadow-sm">New!</div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-3">{{ $cookie['name'] }}</h3>
                    <p class="text-gray-600 text-sm mb-4 h-10">{{ $cookie['description'] }}</p> {{-- h-10 ensures consistent height --}}
                    <div class="flex items-center justify-between mt-6">
                        <span class="text-2xl text-gray-800 font-bold">${{ number_format(rand(250, 450) / 100, 2) }}</span>
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Add to Cart</span>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

@endsection