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

function deleteDotenv()
{
    $dotenv = base_path('.env');

    if (file_exists($dotenv)) {
        $content = file_get_contents($dotenv);
        unlink($dotenv);
    }
}

function createDotenv()
{
    deleteDotenv();
    $dotenv = base_path('.env');

    $host = dotenv()['host'];
    $token = dotenv()['token'];

    $content = <<<EOT
    SENTINEL_ENABLED=true
    SENTINEL_HOST={$host}
    SENTINEL_TOKEN={$token}
    EOT;

    dump($content);

    file_put_contents($dotenv, $content);
}
