<?php


return [
    'mail_from_address' => env('MAIL_FROM_ADDRESS', 'dave@quantumidea.co.uk'),
    'mail_from_name' => env('MAIL_FROM_NAME', 'Quantum Idea'),
    'newsletter_queue' => env('NEWSLETTER_QUEUE', false),
    'public_path' => env('PUBLIC_PATH', '/public'),
    'app_type' => env('APP_TYPE', 'normal'),
    'multisite' => env('MULTISITE', false),
    'multisiteUrl' => env('MULTISITE_URL', false),
    'multisite_sites' => []
];