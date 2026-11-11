<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Storefront Details
    |--------------------------------------------------------------------------
    |
    | Business contact details shown in the footer and on the contact page.
    | Phone numbers come from the WhatsApp contacts managed in the admin.
    |
    */

    'email' => env('STORE_EMAIL', 'africanconfluence@gmail.com'),

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

    'garment_types' => [
        'Agbada', 'Babban Riga', 'Buba and Sokoto', 'Kaftan / Senator', 'Isiagu Set', 'Iro and Buba', 'Wrapper Set (George, Akwete, Ankara)',
        'Bubu / Kaftan Dress', 'Aso Ebi (Group Order)', 'Gele / Ichafu', 'Children’s Traditional Wear',
        'Evening / Reception Gown', 'Bridal Gown', 'Dress / Gown', 'Suit / Tuxedo', 'Corporate / Office Wear', 'Casual Wear',
        'Shirt / Blouse', 'Jacket / Outerwear', 'Trousers / Skirt', 'Other',
    ],

    'fabrics' => ['Ankara', 'Lace', 'Aso-Oke', 'George', 'Adire', 'Akwete', 'Isiagu', 'Sequins', 'Chiffon / Tulle', 'Cotton', 'Silk', 'Wool', 'Linen', 'Satin / Velvet'],

    /*
    | Style filters on the shop page. A product matches a style when its name or
    | summary contains one of the keywords. Aliases are other spellings shoppers
    | type into search (e.g. "baban-riga"), which then select that style.
    */

    'styles' => [
        'babban-riga' => ['label' => 'Babban riga', 'keywords' => ['babban riga'], 'aliases' => ['baban riga', 'babanriga', 'babbanriga', 'babbar riga', 'riga', 'grand boubou']],
        'senator' => ['label' => 'Senator & kaftan', 'keywords' => ['senator', 'long kaftan'], 'aliases' => ['kaftan', 'caftan']],
        'isiagu' => ['label' => 'Isiagu', 'keywords' => ['isiagu'], 'aliases' => ['isi agu', 'chieftaincy']],
        'iro-and-buba' => ['label' => 'Iro and buba', 'keywords' => ['buba'], 'aliases' => ['iro', 'iro and buba', 'buba and iro', 'iro buba']],
        'wrappers' => ['label' => 'Wrappers', 'keywords' => ['wrapper'], 'aliases' => ['wrappers', 'george', 'akwete', 'madras', 'obiakwa', 'double wrapper']],
        'gowns' => ['label' => 'Gowns', 'keywords' => ['gown'], 'aliases' => ['gowns', 'evening gown', 'ball gown', 'mermaid']],
        'gele' => ['label' => 'Gele & head ties', 'keywords' => ['gele', 'ichafu', 'head tie', 'turban'], 'aliases' => ['headtie', 'head wrap', 'headwrap']],
        'lace' => ['label' => 'Lace', 'keywords' => ['lace'], 'aliases' => []],
        'sequins' => ['label' => 'Sequins', 'keywords' => ['sequin'], 'aliases' => ['sequins', 'sequinned']],
        'bubu' => ['label' => 'Bubu', 'keywords' => ['bubu'], 'aliases' => ['boubou', 'kaftan dress']],
        'coral-beads' => ['label' => 'Coral beads', 'keywords' => ['coral'], 'aliases' => ['beads', 'coral beads', 'aka olu']],
        'agbada' => ['label' => 'Agbada', 'keywords' => ['agbada'], 'aliases' => []],
    ],

    'currency' => env('STORE_CURRENCY', 'usd'),

    /*
    | Starting exchange rate (naira per US dollar) until the admin sets one on
    | the Products page. Prices are entered in naira; card payments are charged
    | in the dollar amount worked out from this rate.
    */

    'ngn_per_usd' => (int) env('STORE_NGN_PER_USD', 1500),

    // Starting percentage added to converted dollar prices, until the admin sets one.
    'usd_markup_percent' => (int) env('STORE_USD_MARKUP_PERCENT', 20),

];
