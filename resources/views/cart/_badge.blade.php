@if($totalItems > 0)
    <div id="cart-badge"
         class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center cart-badge">
        {{ $totalItems }}
    </div>
@endif
