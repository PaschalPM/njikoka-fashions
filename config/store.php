<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storefront Details
    |--------------------------------------------------------------------------
    |
    | Business contact details shown in the footer and on the contact page.
    |
    */

    'email' => env('STORE_EMAIL', 'africanconfluence@gmail.com'),

    'phones' => array_filter(explode(',', env('STORE_PHONES', '301-968-0567,227-256-0003'))),

    'address' => env('STORE_ADDRESS', '14502 Greenview Drive Laurel, Maryland 20708'),

    'social' => [
        'instagram' => env('STORE_INSTAGRAM', '#'),
        'facebook' => env('STORE_FACEBOOK', '#'),
        'tiktok' => env('STORE_TIKTOK', '#'),
    ],

    'services' => [
        'Initial Strategy Session (30 Mins)',
        'Comprehensive Business Audit (60 Mins)',
        '1-on-1 Executive Coaching (45 Mins)',
    ],

    'time_slots' => ['09:00 AM', '11:00 AM', '02:00 PM', '04:00 PM'],

    'garment_types' => ['Dress / Gown', 'Suit / Tuxedo', 'Shirt / Blouse', 'Jacket / Outerwear', 'Trousers / Skirt', 'Other'],

    'fabrics' => ['Cotton', 'Silk', 'Wool', 'Linen', 'Satin / Velvet'],

    'currency' => env('STORE_CURRENCY', 'usd'),

];
