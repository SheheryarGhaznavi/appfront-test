<?php

return [
    'default_exchange_rate' => env('DEFAULT_EXCHANGE_RATE', 0.85),
    'notification_email' => env('PRICE_NOTIFICATION_EMAIL', 'admin@example.com'),
    'image' => [
        'max_size' => 2048, // KB
        'default' => 'product-placeholder.jpg',
    ],
];