<?php

return [
    /**
     * If you want to disable Sentinel, set `SENTINEL_ENABLED` to `false`.
     */
    'enabled' => env('SENTINEL_ENABLED', true),
    /**
     * Sentinel host where your application is registered.
     */
    'host' => env('SENTINEL_HOST', 'http://app.sentinel.test'),
    /**
     * Token is used to authenticate your application with Sentinel.
     */
    'token' => env('SENTINEL_TOKEN'),
    /**
     * If you want to throw Sentinel errors for debug, set `SENTINEL_DEBUG` to `true`.
     * WARNING: do not use it on production.
     */
    'debug' => env('SENTINEL_DEBUG', false),
];
