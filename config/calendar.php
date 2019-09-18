<?php


return [
    'google_api_key' => env('GOOGLE_API_KEY', 'AIzaSyAOv3tffAMN-qxxGS0ohoaSeNF_2n8srks'),

    'categoryClass' => '\\Quantum\\base\\Models\\Categories',

    'categoryPivotName' => 'calendar_categories',

    'members_routes' => false,

    'events' => [
        'Site' => 'Green',
        'Dealers' => '#F40000',
    ],

    'events_legend' => [
        'Site' => 'Site Events',
        'Dealers' => 'Auctions',
    ],

    'default_types' => [
        'App\Models\Dealers' => null
    ],

    'use_categories' => true,
    'category_slugs' => ['main']
];