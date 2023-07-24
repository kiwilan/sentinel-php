<?php

use Dotenv\Dotenv;
use Kiwilan\Sentinel\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function dotenv(): array
{
    $dotenv = Dotenv::createMutable(getcwd());
    $data = $dotenv->load();

    $enabled = $data['TEST_SENTINEL_ENABLED'] ?? true;
    $host = $data['TEST_SENTINEL_HOST'] ?? 'http://app.sentinel.test';
    $token = $data['TEST_SENTINEL_TOKEN'] ?? null;

    return [
        'enabled' => $enabled,
        'host' => $host,
        'token' => $token,
    ];
}
