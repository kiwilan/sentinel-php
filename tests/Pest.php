<?php

use Dotenv\Dotenv;
use Kiwilan\Sentinel\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function dotenv(): array
{
    $dotenv = Dotenv::createMutable(getcwd());
    $data = $dotenv->load();

    $enabled = $data['SENTINEL_ENABLED_TEST'] ?? true;
    $host = $data['SENTINEL_HOST_TEST'] ?? 'http://app.sentinel.test';
    $token = $data['SENTINEL_TOKEN_TEST'] ?? null;

    return [
        'enabled' => $enabled,
        'host' => $host,
        'token' => $token,
    ];
}
