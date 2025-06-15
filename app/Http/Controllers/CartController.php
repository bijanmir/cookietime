<?php

namespace App\Http\Controllers;

use App\Models\Cookie;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $cartItems = [];

        foreach ($cart as $id => $quantity) {
            $cookie = Cookie::find($id);
            if ($cookie) {
                $cartItems[] = array_merge($cookie, ['quantity' => $quantity]);
            }
        }

        return view('cart.index', compact('cartItems'));
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $quantity = max(0, (int) $request->input('quantity'));

        $cart = session('cart', []);

        if ($quantity > 0) {
            $cart[$id] = $quantity;
        } else {
            unset($cart[$id]);
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'totalItems' => array_sum($cart),
            'cart' => $cart
        ]);
    }

    public function remove(Request $request)
    {
        $id = $request->input('id');
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        // Get updated cart items for rendering
        $cartItems = [];
        foreach ($cart as $cookieId => $quantity) {
            $cookie = Cookie::find($cookieId);
            if ($cookie) {
                $cartItems[] = array_merge($cookie, ['quantity' => $quantity]);
            }
        }

        $cartHtml = view('cart._items', compact('cartItems'))->render();
        $badgeHtml = view('cart._badge', ['totalItems' => array_sum($cart)])->render();

        return response()->json([
            'success' => true,
            'html' => $cartHtml,
            'badge' => $badgeHtml,
            'totalItems' => array_sum($cart),
            'cart' => $cart
        ]);
    }

    public function partial()
    {
        $cart = session('cart', []);
        $cartItems = [];

        foreach ($cart as $id => $quantity) {
            $cookie = Cookie::find($id);
            if ($cookie) {
                $cartItems[] = array_merge($cookie, ['quantity' => $quantity]);
            }
        }

        return view('cart._items', compact('cartItems'));
    }

    public function badge()
    {
        $cart = session('cart', []);
        $totalItems = array_sum($cart);

        return view('cart._badge', compact('totalItems'));
    }

    public function sync(Request $request)
    {
        if ($request->isMethod('post')) {
            $clientCart = $request->input('cart', []);
            session(['cart' => $clientCart]);

            return response()->json([
                'success' => true,
                'totalItems' => array_sum($clientCart)
            ]);
        }

        // GET request - return server cart
        $cart = session('cart', []);
        return response()->json([
            'cart' => $cart,
            'totalItems' => array_sum($cart)
        ]);
    }
}
