<?php

return [
    'enabled' => env('SENTINEL_ENABLED', false),
    'host' => env('SENTINEL_HOST', 'http://app.sentinel.test'),
    'token' => env('SENTINEL_TOKEN'),

    'notifications' => [
        'enabled' => env('SENTINEL_NOTIFICATIONS_ENABLED', false),
        'service' => env('SENTINEL_NOTIFICATION_SERVICE', 'discord'),
        'token' => env('SENTINEL_NOTIFICATION_TOKEN'),
        // 'email' => env('SENTINEL_NOTIFICATION_EMAIL'),
    ],
];
