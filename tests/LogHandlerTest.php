<?php

use Kiwilan\Sentinel\Log\LogHandler;
use Kiwilan\Sentinel\SentinelConfig;

it('can generate log handler', function () {
    $exception = new \Exception('This is a test exception', 500);
    $error = LogHandler::make($exception);

    expect($error)->toBeInstanceOf(LogHandler::class);

    expect($error->app())->toBe('Laravel');
    expect($error->env())->toBe('testing');
    expect($error->isProduction())->toBeFalse();
    expect($error->url())->toBe('http://localhost');
    expect($error->method())->toBe('GET');
    expect($error->userAgent())->toBe('Symfony');
    expect($error->ip())->toBe('127.0.0.1');
    expect($error->basePath())->toContain('vendor/orchestra/testbench-core/laravel');
    expect($error->current())->toBeInstanceOf(\Kiwilan\Sentinel\Log\LogMessage::class);

    $message = $error->current();

    expect($message->code())->toBe(500);
    expect($message->file())->toContain('tests/LogHandlerTest.php');
    expect($message->line())->toBeInt();
    expect($message->message())->toBe('This is a test exception');
    expect($message->trace())->toBeArray();
    expect($message->traceString())->toBeString();
    expect($message->toArray())->toBeArray();
});

it('can use sentinel config', function () {
    $config = SentinelConfig::toArray();
    $dotenv = dotenv();

    expect($config)->toBeArray();
    expect($config['enabled'])->toBeTrue();
    expect($config['host'])->toBe($dotenv['host']);
    expect($config['token'])->toBe($dotenv['token']);
});
