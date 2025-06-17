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
                'name' => 'Birthday Bash Kit',
                'description' => 'Celebrate in style! Includes funfetti cookies, bright frosting colors, rainbow sprinkles, and candles.',
                'image' => '/images/kits/birthday-bash.png',
                'price' => 24.99
            ],
            [
                'id' => 2,
                'name' => 'Holiday Magic Kit',
                'description' => 'Festive sugar cookies shaped like trees, stars, and stockings. Comes with red, green, and white frosting and sparkly sprinkles.',
                'image' => '/images/kits/holiday-magic.png',
                'price' => 26.99
            ],
            [
                'id' => 3,
                'name' => 'Dino Discovery Kit',
                'description' => 'Roar! This kit features dinosaur-shaped cookies, dino-green frosting, and bone sprinkles. Great for kids!',
                'image' => '/images/kits/dino-discovery.png',
                'price' => 22.50
            ],
            [
                'id' => 4,
                'name' => 'Love Bites Kit',
                'description' => 'Heart-shaped cookies, blush pink and red frosting, and edible glitter make this the sweetest way to say “I love you.”',
                'image' => '/images/kits/love-bites.png',
                'price' => 25.00
            ],
            [
                'id' => 5,
                'name' => 'Spooky Sweets Kit',
                'description' => 'Ghosts, bats, pumpkins and more! Comes with orange, purple, and black frosting plus creepy candy eyes.',
                'image' => '/images/kits/spooky-sweets.png',
                'price' => 23.99
            ],
            [
                'id' => 6,
                'name' => 'Winter Wonderland Kit',
                'description' => 'Snowflake and mitten cookies paired with icy blue and white frosting, shimmer dust, and snowflake sprinkles.',
                'image' => '/images/kits/winter-wonderland.png',
                'price' => 26.50
            ],
            [
                'id' => 7,
                'name' => 'Under the Sea Kit',
                'description' => 'Starfish, mermaids, and seashells with aqua frosting and pearly sugar pearls. A splash of fun for everyone!',
                'image' => '/images/kits/under-the-sea.png',
                'price' => 24.75
            ],
            [
                'id' => 8,
                'name' => 'Galaxy Glaze Kit',
                'description' => 'Out-of-this-world cookies with black cocoa dough, neon icing, and cosmic sprinkles. Great for parties and stargazers!',
                'image' => '/images/kits/galaxy-glaze.png',
                'price' => 27.00
            ],
            [
                'id' => 9,
                'name' => 'Build-A-Bunny Kit',
                'description' => 'Perfect for spring! Bunny and egg cookies with pastel frosting, candy whiskers, and marshmallow tails.',
                'image' => '/images/kits/build-a-bunny.png',
                'price' => 23.50
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
