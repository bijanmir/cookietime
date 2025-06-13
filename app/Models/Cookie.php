<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cookie extends Model
{
    use HasFactory;

    /**
     * Simulate getting all cookies from a database.
     */
    public static function all($key = null)
    {
        return [
            [
                'id' => 1,
                'name' => 'Chocolate Chip Supreme',
                'description' => 'The classic, perfected. Loaded with rich chocolate chunks.',
                'image' => '/images/chocolate-chip.png', // We'll add images later
            ],
            [
                'id' => 2,
                'name' => 'Oatmeal Raisin Bliss',
                'description' => 'A chewy, spiced cookie that feels like a warm hug.',
                'image' => '/images/oatmeal-raisin.png',
            ],
            [
                'id' => 3,
                'name' => 'Double Fudge Delight',
                'description' => 'For the ultimate chocolate lover. Rich, dark, and decadent.',
                'image' => '/images/double-fudge.png',
            ],
            [
                'id' => 4,
                'name' => 'Peanut Butter Heaven',
                'description' => 'Salty, sweet, and unbelievably soft. A peanut butter paradise.',
                'image' => '/images/peanut-butter.png',
            ],
        ];
    }
}