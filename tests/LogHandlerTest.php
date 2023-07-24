<?php

use Kiwilan\Sentinel\Log\LogHandler;
use Kiwilan\Sentinel\Sentinel;

it('can generate log handler', function () {
    $exception = new \Exception('This is a test exception', 500);
    $error = LogHandler::make($exception);

    Sentinel::make($exception, dotenv()['token']);

    expect($error)->toBeInstanceOf(LogHandler::class);
    expect(true)->toBeTrue();
});

it('can fail log handler', function () {
    expect(fn () => Sentinel::make(new Exception('This is a test exception', 500)))->toThrow(Exception::class);
});
