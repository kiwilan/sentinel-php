<?php

use Kiwilan\Sentinel\Facades\Sentinel;

beforeEach(function () {
    createDotenv();
});

it('can generate log handler', function () {
    $exception = new \Exception('This is a test exception', 500);
    // $error = LogHandler::make($exception);

    $instance = Sentinel::register($exception);
    // dump($instance);

    expect($instance)->toBeArray();
    expect($instance['message'])->toBe('success');
});

// it('can fail log handler', function () {
//     expect(fn () => Sentinel::make(new Exception('This is a test exception', 500)))->toThrow(Exception::class);
// });
