<?php

namespace Kiwilan\Sentinel;

class SentinelConfig
{
    public static function enabled(): bool
    {
        $config = config('sentinel.enabled');
        $env = env('SENTINEL_ENABLED');

        if (! empty($config)) {
            return $config;
        }

        if (! empty($env)) {
            return $env;
        }

        return true;
    }

    public static function host(): string
    {
        $config = config('sentinel.host');
        $env = env('SENTINEL_HOST');

        if (! empty($config)) {
            return $config;
        }

        if (! empty($env)) {
            return $env;
        }

        return 'http://app.sentinel.test';
    }

    public static function token(): string
    {
        $config = config('sentinel.token');
        $env = env('SENTINEL_TOKEN');

        if (! empty($config)) {
            return $config;
        }

        if (! empty($env)) {
            return $env;
        }

        return '';
    }
}
