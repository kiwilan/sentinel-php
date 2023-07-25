<?php

use Dotenv\Dotenv;
use Kiwilan\Sentinel\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function dotenv(): array
{
    $dotenv = file_get_contents(getcwd().'/.env');
    dump($dotenv);

    $dotenv = Dotenv::createMutable(getcwd());
    $data = $dotenv->load();
    dump($data);

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

    dump($host);
    dump($token);

    $content = <<<EOT
    SENTINEL_ENABLED=true
    SENTINEL_HOST={$host}
    SENTINEL_TOKEN={$token}
    EOT;

    file_put_contents($dotenv, $content);
}
