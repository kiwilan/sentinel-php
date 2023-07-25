<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Sentinel\Commands\SentinelTestCommand;
use function Pest\Laravel\artisan;

it('can install', function () {
    $dotenv = getcwd().'/.env';
    $testHandler = getcwd().'/tests/Exceptions/Handler.php';
    $host = dotenv()['host'];
    $token = dotenv()['token'];

    Artisan::call('sentinel:install', [
        '--host' => $host,
        '--token' => $token,
        '--handler' => $testHandler,
        '--dotenv' => $dotenv,
    ]);

    $handlerContent = file_get_contents($testHandler);
    $dotenvContent = file_get_contents($dotenv);

    expect($handlerContent)->toContain('\Kiwilan\Sentinel\Facades\Sentinel::register($e);');
    expect($dotenvContent)->toContain('SENTINEL_ENABLED=true');
    expect($dotenvContent)->toContain("SENTINEL_HOST={$host}");
    expect($dotenvContent)->toContain("SENTINEL_TOKEN={$token}");

    unlink($testHandler);
    copy(getcwd().'/tests/Media/Handler.php', $testHandler);
});

it('can be test with command', function () {
    artisan(SentinelTestCommand::class)
        ->assertSuccessful();
});
