<?php

use Kiwilan\Sentinel\Facades\Sentinel;

beforeEach(function () {
    createDotenv();
});

it('can generate log handler', function () {
    $exception = new \Exception('This is a test exception', 500);

    $instance = Sentinel::make();
    $response = Sentinel::register($exception);
    dump($instance);
    dump($response);

    $status = $response['status'] ?? null;
    $message = $response['json']['message'] ?? null;

    expect($response)->toBeArray();
    expect($message)->toBe('success');
    expect($status)->toBe(200);
});

// it('can fail log handler', function () {
//     expect(fn () => Sentinel::make(new Exception('This is a test exception', 500)))->toThrow(Exception::class);
// });
