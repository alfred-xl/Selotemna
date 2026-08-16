<?php

return [
    'phone' => env('SELOTEMNA_PHONE'),
    'whatsapp' => env('SELOTEMNA_WHATSAPP'),
    'email' => env('SELOTEMNA_EMAIL'),
    'address' => env('SELOTEMNA_ADDRESS'),
    'business_hours' => env('SELOTEMNA_BUSINESS_HOURS'),

    'featured_property' => [
        'name' => 'Omu Creek',
        'type' => 'Land allocation',
        'title' => 'Certificate of Occupancy (C of O)',
        'price_per_sqm' => 50000,
        'options' => [
            ['size_sqm' => 300, 'price' => 15000000],
            ['size_sqm' => 500, 'price' => 25000000],
            ['size_sqm' => 1000, 'price' => 50000000],
        ],
        'disclaimer' => 'Prices exclude applicable taxes. Availability and property information are subject to confirmation.',
        'video_url' => env('SELOTEMNA_OMU_CREEK_VIDEO_URL'),
        'video_poster' => env('SELOTEMNA_OMU_CREEK_VIDEO_POSTER'),
    ],
];
