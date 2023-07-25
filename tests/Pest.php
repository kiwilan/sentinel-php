<?php

use Dotenv\Dotenv;
use Kiwilan\Sentinel\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);
createDotenv();

function dotenv(): array
{
    $dotenv = file_get_contents(getcwd().'/.env');

    $dotenv = Dotenv::createMutable(getcwd());
    $data = $dotenv->load();

    $enabled = $data['SENTINEL_ENABLED'] ?? true;
    $host = $data['SENTINEL_HOST'] ?? 'http://app.sentinel.test';
    $token = $data['SENTINEL_TOKEN'] ?? null;

    return [
        'enabled' => $enabled,
        'host' => $host,
        'token' => $token,
    ];
}

function createDotenv()
{
    $baseDotenv = getcwd().'/.env';
    $dotenvPath = getcwd().'/vendor/orchestra/testbench-core/laravel/.env';

    if (file_exists($dotenvPath)) {
        unlink($dotenvPath);
    }

    copy($baseDotenv, $dotenvPath);
}
