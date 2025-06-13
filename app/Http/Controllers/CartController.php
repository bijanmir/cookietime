<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // You can pass cart data to the view here
        return view('cart.index');
    }
}