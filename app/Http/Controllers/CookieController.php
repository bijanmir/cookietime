<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cookie;

class CookieController extends Controller
{
    //

    public function showCookieOfTheDay()
    {
        // In a real app, you'd get this from a database!
        $cookies = [
            'Chocolate Chip Supreme',
            'Oatmeal Raisin Bliss',
            'Double Fudge Delight',
            'Peanut Butter Heaven'
        ];
        $todaysCookie = $cookies[array_rand($cookies)];

        // This returns ONLY the HTML fragment to HTMX
        return <<<HTML
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Today's Special:</h2>
                <p class="text-3xl text-pink-500 animate-pulse mt-2">$todaysCookie</p>
            </div>
        HTML;
    }

    public function index()
    {
        // Get all cookies from the model
        $cookies = Cookie::all();

        // Return a view with the cookies data
        return view('cookies.index', compact('cookies'));
    }
}
