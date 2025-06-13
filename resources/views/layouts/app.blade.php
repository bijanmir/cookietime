<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cookie Time!</title>

    {{-- Custom Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    {{-- Tailwind CSS via Vite --}}
    @vite('resources/css/app.css')
</head>
<body class="antialiased font-sans bg-black/90 text-gray-800">

    {{-- Navigation Bar --}}
   <nav class="flex justify-between items-center px-6 py-4 text-white   w-full z-50">
    {{-- Left: Hamburger Menu Icon --}}
    <img src="/images/icons/hamburger-white.png" alt="" class="w-9 h-9 cursor-pointer">

    {{-- Center: Logo --}}
    <div class="text-center">
        <a href="{{ url('/') }}" class="text-3xl font-bold font-cursive tracking-wide">
            <img src="/images/cookietime_logo.png" alt="CookieTime" class="h-50 mx-auto">
        </a>
    </div>

    {{-- Right: Cart Icon with Badge --}}
    <div class="relative">
        <a href="{{ route('cart.index') }}" class="w-8 h-8 flex items-center justify-center border border-white rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.2 6.4A1 1 0 007 21h10a1 1 0 001-1.2L16.6 13M7 13l2-6h6l2 6"/>
            </svg>
        </a>
        <span class="absolute -top-2 -right-2 bg-red-500 text-xs text-white font-bold px-1.5 py-0.5 rounded-full">
            1
        </span>
    </div>
</nav>


    {{-- Main Content Area --}}
    <main id="content" class="w-full">
        @yield('content')
    </main>

    {{-- HTMX Script --}}
    <script src="https://unpkg.com/htmx.org@1.9.10" integrity="sha384-D1Kt99CQMDuVetoL1lrYwg5t+9QdHe7NLX/SoJYkXDFfX37iInKRy5xLSi8nO7UC" crossorigin="anonymous"></script>
</body>
</html>
