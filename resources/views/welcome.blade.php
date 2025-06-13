@extends('layouts.app')

@section('content')
    <div class="bg-zinc-900 text-zinc-300 font-sans">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

            {{--
                THE GRID LOGIC:
                - Mobile (default): A single column (`grid-cols-1`). Everything stacks automatically.
                - Desktop (`lg:`): A 2-column, 3-row grid (`lg:grid-cols-2 lg:grid-rows-3`).
            --}}
            <div class="grid lg:grid-cols-2 lg:grid-rows-3 gap-6">

                {{--
                    The Hero Image.
                    On desktop, we tell it to span all 3 rows (`lg:row-span-3`),
                    locking it to the left column.
                --}}
                <div class="lg:row-span-3 rounded-2xl overflow-hidden group shadow-lg">
                    <img src="https://i.etsystatic.com/20070846/r/il/9b9dcc/2354242698/il_fullxfull.2354242698_foka.jpg" alt="A lifestyle shot of delicious cookies"
                         class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105">
                </div>

                {{-- Promo 1: Build a Box. On desktop, this automatically flows into column 2, row 1. --}}
                <div class="bg-zinc-800 rounded-2xl p-8 flex flex-col justify-center text-center ring-1 ring-white/10 shadow-lg h-full">
                    <p class="text-sm text-zinc-400 uppercase tracking-widest">Your Cravings, Your Rules</p>
                    <h2 class="text-4xl font-bold text-amber-400 mt-2 mb-3">Build a Box Check</h2>
                    <p class="text-zinc-300 mb-6">Hand-pick your favorites to create the perfect dozen.</p>
                    <a href="#"
                       class="bg-amber-400 text-zinc-900 font-bold py-3 px-8 rounded-full self-center
                          hover:bg-amber-300 hover:shadow-lg hover:shadow-amber-400/20 transition-all duration-300 ease-in-out">
                        Start Creating
                    </a>
                </div>

                {{-- Promo 2: Variety Pack. On desktop, this flows into column 2, row 2. --}}
                <div class="relative rounded-2xl overflow-hidden shadow-lg group h-full min-h-[250px]">
                    <img src="https://thegracefulbakershop.com/cdn/shop/files/IMG_33264.jpg?v=1715784095" alt="A variety pack of cookies" class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end items-center text-center p-6">
                        <h2 class="text-3xl font-bold text-white">Variety Pack</h2>
                        <p class="text-zinc-200 mt-1 mb-4">The best of what's baking right now.</p>
                        <div class="space-x-3">
                            <a href="#" class="bg-white/90 text-black font-semibold py-2 px-5 rounded-full text-sm hover:bg-white transition">Add to Cart</a>
                            <a href="#" class="text-white font-semibold underline decoration-amber-400 underline-offset-4 text-sm hover:text-amber-300 transition">Learn More</a>
                        </div>
                    </div>
                </div>

                {{-- Promo 3: Jam Session. On desktop, this flows into column 2, row 3. --}}
                <div class="relative rounded-2xl overflow-hidden shadow-lg group h-full min-h-[250px]">
                    <img src="https://cdn.prod.website-files.com/5ce56b0f27a2154164088913/629ca34ac8ec9f112c2658ea_ice_cream_platter_thumb.jpg" alt="A fruit-jam-filled cookie" class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end items-center text-center p-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-amber-400">Limited Time</p>
                        <h2 class="text-3xl font-bold text-white mt-1">Jam Session</h2>
                        <p class="text-zinc-200 mt-1 mb-4">Our signature cookie with a gooey, fruity center.</p>
                        <div class="space-x-3">
                            <a href="#" class="bg-amber-400 text-zinc-900 font-semibold py-2 px-5 rounded-full text-sm hover:bg-amber-300 transition">Order Now</a>
                            <a href="#" class="text-white font-semibold underline decoration-amber-400 underline-offset-4 text-sm hover:text-amber-300 transition">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Featured Flavors Section --}}
            <div class="text-center mt-20 py-10 border-t-2 border-zinc-800">
                <h3 class="text-sm uppercase text-zinc-500 tracking-widest mb-4">Straight from the oven</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-x-8 gap-y-4 text-xl md:text-2xl font-bold text-zinc-200">
                  <div>
                      <img src="/images/hero-cookies.jpg" alt="">
                      <a href="#" class="hover:text-amber-400 transition">JAM SESSION</a>
                  </div>
                    <div>
                        <img src="/images/hero-cookies.jpg" alt="">
                        <a href="#" class="hover:text-amber-400 transition">COMING OUT HOT</a>
                    </div>
                    <div>
                        <img src="/images/hero-cookies.jpg" alt="">
                        <a href="#" class="hover:text-amber-400 transition">CINNA-DUNK'D</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
