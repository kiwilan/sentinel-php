<?php

// use Illuminate\Support\Facades\Artisan;

// it('can install', function () {
//     $dotenv = getcwd().'/.env';
//     $testHandler = getcwd().'/tests/Exceptions/Handler.php';
//     $host = dotenv()['host'];
//     $token = dotenv()['token'];
//     Artisan::call('sentinel:install', [
//         '--host' => $host,
//         '--token' => $token,
//         '--handler' => $testHandler,
//         '--dotenv' => $dotenv,
//     ]);

//     Artisan::call('sentinel:install', [
//         '--host' => $host,
//         '--token' => $token,
//         '--handler' => $testHandler,
//     ]);

//     $content = file_get_contents($testHandler);
//     $dotenvContent = file_get_contents($dotenv);

//     expect($content)->toContain('\Kiwilan\Sentinel\Sentinel::make($e);');
//     expect($dotenvContent)->toContain('SENTINEL_ENABLED_TEST=true');
//     expect($dotenvContent)->toContain("SENTINEL_HOST_TEST={$host}");
//     expect($dotenvContent)->toContain("SENTINEL_TOKEN={$token}");

//     unlink($testHandler);
//     copy(getcwd().'/tests/Media/Handler.php', $testHandler);

//     Artisan::call('sentinel:test', [
//         '--host' => $host,
//         '--token' => $token,
//     ]);
// })->with(['add', 'replace']);
