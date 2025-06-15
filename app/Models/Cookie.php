<?php

// ================================
// 1. COOKIE MODEL
// ================================
// App/Models/Cookie.php

namespace App\Models;

class Cookie
{
    public static function all()
    {
        return [
            [
                'id' => 1,
                'name' => 'Chocolate Chip Supreme',
                'description' => 'Rich chocolate chips baked into our signature cookie dough. A classic favorite that never goes out of style.',
                'image' => '/images/chocolate-chip.png',
                'price' => 2.50
            ],
            [
                'id' => 2,
                'name' => 'Oatmeal Raisin Bliss',
                'description' => 'Hearty oats and plump raisins create the perfect chewy texture. Made with love and cinnamon spice.',
                'image' => '/images/oatmeal-raisin.png',
                'price' => 2.25
            ],
            [
                'id' => 3,
                'name' => 'Double Fudge Brownie',
                'description' => 'Decadent double chocolate cookie with fudge chunks. For serious chocolate lovers only.',
                'image' => '/images/double-fudge.png',
                'price' => 3.00
            ],
            [
                'id' => 4,
                'name' => 'Snickerdoodle Delight',
                'description' => 'Soft and chewy cookies rolled in cinnamon sugar. A nostalgic treat that brings back memories.',
                'image' => '/images/snickerdoodle.png',
                'price' => 2.75
            ],
            [
                'id' => 5,
                'name' => 'Peanut Butter Paradise',
                'description' => 'Creamy peanut butter cookies with the perfect balance of sweet and salty. Simply irresistible.',
                'image' => '/images/peanut-butter.png',
                'price' => 2.50
            ],
            [
                'id' => 6,
                'name' => 'White Chocolate Macadamia',
                'description' => 'Premium white chocolate and crunchy macadamia nuts in every bite. Pure luxury.',
                'image' => '/images/white-chocolate-macadamia.png',
                'price' => 3.25
            ]
        ];
    }

    public static function find($id)
    {
        $cookies = self::all();
        foreach ($cookies as $cookie) {
            if ($cookie['id'] == $id) {
                return $cookie;
            }
        }
        return null;
    }
}
