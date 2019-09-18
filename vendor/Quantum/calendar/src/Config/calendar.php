<?php


return [
    'google_api_key' => env('GOOGLE_API_KEY', 'AIzaSyAOv3tffAMN-qxxGS0ohoaSeNF_2n8srks'),

    'categoryClass' => '\\Quantum\\base\\Models\\Categories',

    'categoryPivotName' => 'calendar_categories',

    'members_routes' => true,

    'events' => [
        'Site' => 'Green',
        'User' => 'Blue',
    ],

    'events_legend' => [
        'Site' => 'Site Events',
        'User' => 'Your Personal Events',
    ],

    'default_types' => [
    ],

    'use_categories' => true,
    'category_slugs' => ['main', 'main-2'],
    'userEvent' => true
];