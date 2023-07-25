<?php

use Kiwilan\Sentinel\Facades\Sentinel;

beforeEach(function () {
    createDotenv();
});

it('can generate log handler', function () {
    $exception = new \Exception('This is a test exception', 500);
    // $error = LogHandler::make($exception);

    $response = Sentinel::register($exception);
    // dump($instance);

    $status = $response['status'] ?? null;
    $message = $response['json']['message'] ?? null;

    expect($response)->toBeArray();
    expect($message)->toBe('success');
});

// it('can fail log handler', function () {
//     expect(fn () => Sentinel::make(new Exception('This is a test exception', 500)))->toThrow(Exception::class);
// });
