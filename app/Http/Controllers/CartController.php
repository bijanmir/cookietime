<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = [
            ['name' => 'Chocolate Chunk Cookie', 'qty' => 2, 'price' => 4.00, 'image' => '/images/sample-cookie1.jpg'],
            ['name' => 'Peanut Butter Swirl', 'qty' => 1, 'price' => 4.50, 'image' => '/images/sample-cookie2.jpg'],
        ];

        $subtotal = collect($cartItems)->sum(fn($item) => $item['qty'] * $item['price']);

        return view('cart.index', compact('cartItems', 'subtotal'));

    }
}
