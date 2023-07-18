<?php

namespace Kiwilan\Sentinel;

class SentinelConfig
{
    public static function enabled(): bool
    {
        return config('sentinel.enabled') ?? false;
    }

    public static function host(): string
    {
        return config('sentinel.host') ?? 'http://app.sentinel.test';
    }

    public static function token(): string
    {
        return config('sentinel.token');
    }

    public static function notificationsEnabled(): bool
    {
        return config('sentinel.notifications.enabled') ?? false;
    }

    public static function notificationsService(): string
    {
        return config('sentinel.notifications.service') ?? 'discord';
    }

    public static function notificationsToken(): string
    {
        return config('sentinel.notifications.token');
    }

    public static function notificationsEmail(): string
    {
        return config('sentinel.notifications.email');
    }
}
