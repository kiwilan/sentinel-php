<?php

use Kiwilan\Sentinel\Facades\Sentinel;

it('can use sentinel', function () {
    $instance = Sentinel::make();

    expect($instance)->toBeInstanceOf(\Kiwilan\Sentinel\Sentinel::class);
    expect($instance->token())->toBeString();
    expect($instance->host())->toBeString();
    expect($instance->enabled())->toBeTrue();
    expect($instance->status())->toBe(0);
    expect($instance->payload())->toBeArray();
    expect($instance->message())->toBe('Unknown error');
    expect($instance->error())->toBeNull();
    expect($instance->user())->toBeNull();
    expect($instance->toArray())->toBeArray();
});

it('can register sentinel', function () {
    $exception = new \Exception('This is a test exception', 500);
    $response = Sentinel::register($exception);

    $status = $response['status'] ?? null;
    $message = $response['body']['message'] ?? null;

    expect($response)->toBeArray();
    expect($message)->toBe('success');
    expect($status)->toBe(200);
});
