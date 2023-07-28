<?php

use Kiwilan\Sentinel\Commands\SentinelInstallCommand;
use Kiwilan\Sentinel\Commands\SentinelTestCommand;
use Kiwilan\Sentinel\Facades\Sentinel;
use function Pest\Laravel\artisan;

beforeEach(function () {
    config(['sentinel.enabled' => false]);
    config(['sentinel.host' => null]);
    config(['sentinel.token' => null]);
});

afterAll(function () {
    createDotenv();
});

it('can use sentinel', function () {
    $instance = Sentinel::make();

    expect($instance->enabled())->toBeFalse();
    expect($instance->host())->toBeString();
    expect($instance->token())->toBeNull();
});

it('can fail on installation', function () {
    artisan(SentinelInstallCommand::class)
        ->expectsQuestion('What is your Sentinel URL?', 'http://app.sentinel.test')
        ->expectsQuestion('What is your application token?', 'token');
});

it('can fail on testing', function () {
    artisan(SentinelTestCommand::class)
        ->assertFailed();
});

it('can fail on sentinel', function () {
    config(['sentinel.enabled' => true]);
    config(['sentinel.host' => null]);
    config(['sentinel.token' => null]);

    $exception = new \Exception('This is a test exception', 500);
    expect(fn () => Sentinel::register($exception, true))->toThrow(\Exception::class);
});

it('can fail on sentinel host', function () {
    config(['sentinel.enabled' => true]);
    config(['sentinel.host' => 'host']);
    config(['sentinel.token' => 'token']);

    $exception = new \Exception('This is a test exception', 500);
    expect(fn () => Sentinel::register($exception, true))->toThrow(\Exception::class);
});

it('can fail on sentinel token', function () {
    config(['sentinel.enabled' => true]);
    config(['sentinel.token' => 'token']);

    $exception = new \Exception('This is a test exception', 500);
    expect(fn () => Sentinel::register($exception, true))->toThrow(\Exception::class);
});
