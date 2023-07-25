# Sentinel for Laravel

[![PHP Version][php-version-src]](php-version-href)
[![Laravel Version][laravel-version-src]](laravel-version-href)

[![Latest Version on Packagist][version-src]](version-href)
[![GitHub Tests Action Status][tests-src]](tests-href)
[![GitHub Code Style Action Status][style-src]](style-href)
[![Total Downloads][downloads-src]](downloads-href)
[![License][license-src]][license-href]
[![codecov][codecov-src]][codecov-href]

PHP package for Laravel to send errors to [**Sentinel**](https://github.com/kiwilan/sentinel).

> **Note**
>
> Sentinel is an open-source error tracking tool. It's currently in development and not ready for production use.

## Installation

You can install the package via composer:

```bash
composer require kiwilan/sentinel-laravel
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="sentinel-laravel-config"
```

This is the contents of the published config file:

```php
return [
  'enabled' => env('SENTINEL_ENABLED', true),
  'host' => env('SENTINEL_HOST', 'http://app.sentinel.test'),
  'token' => env('SENTINEL_TOKEN'),
];
```

## Usage

### Automatic

Just execute the `sentinel:install` Artisan command. It will automatically install the package and configure it for you.

```bash
php artisan sentinel:install
```

### Manual

In `app/Exceptions/Handler.php`

```php
<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Kiwilan\Sentinel\Facades\Sentinel;

class Handler extends ExceptionHandler
{
  /**
   * Register the exception handling callbacks for the application.
   */
  public function register(): void
  {
    $this->reportable(function (Throwable $e) {
      Sentinel::register($e);
    });
  }
}
```

## Verify your installation

Use the `sentinel:test` Artisan command to verify your installation.

```bash
php artisan sentinel:test
```

You can verify your installation by throwing an exception from a route or controller.

For example, in `routes/web.php`:

```php
Route::get('/debug-sentinel', function () {
  throw new \Exception('Sentinel error!');
});
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [Ewilan Rivière](https://github.com/kiwilan)
-   [`spatie`](https://github.com/spatie) for `spatie/laravel-package-tools`
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[<img src="https://user-images.githubusercontent.com/48261459/201463225-0a5a084e-df15-4b11-b1d2-40fafd3555cf.svg" height="120rem" width="100%" />](https://github.com/kiwilan)

[php-version-src]: https://img.shields.io/static/v1?style=flat-square&label=PHP&message=v8.1&color=777BB4&logo=php&logoColor=ffffff&labelColor=18181b
[php-version-href]: https://www.php.net/
[laravel-version-src]: https://img.shields.io/static/v1?style=flat-square&label=Laravel&message=v10&color=FF2D20&logo=laravel&logoColor=ffffff&labelColor=18181b
[laravel-version-href]: https://laravel.com/
[version-src]: https://img.shields.io/packagist/v/kiwilan/sentinel-laravel.svg?style=flat-square&colorA=18181B&colorB=777BB4
[version-href]: https://packagist.org/packages/kiwilan/sentinel-laravel
[tests-src]: https://img.shields.io/github/actions/workflow/status/kiwilan/sentinel-laravel/run-tests.yml?branch=main&label=tests&style=flat-square&labelColor=18181b
[tests-href]: https://github.com/kiwilan/sentinel-laravel/actions?query=workflow%3Arun-tests+branch%3Amain
[style-src]: https://img.shields.io/github/actions/workflow/status/kiwilan/sentinel-laravel/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square&labelColor=18181b
[style-href]: https://github.com/kiwilan/sentinel-laravel/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain
[downloads-src]: https://img.shields.io/packagist/dt/kiwilan/sentinel-laravel.svg?style=flat-square&colorA=18181B&colorB=777BB4
[downloads-href]: https://packagist.org/packages/kiwilan/sentinel-laravel
[license-src]: https://img.shields.io/github/license/kiwilan/sentinel-laravel.svg?style=flat-square&colorA=18181B&colorB=777BB4
[license-href]: https://github.com/kiwilan/sentinel-laravel/blob/main/README.md
[codecov-src]: https://codecov.io/gh/kiwilan/sentinel-laravel/branch/main/graph/badge.svg?token=hytpoIyU92
[codecov-href]: https://codecov.io/gh/kiwilan/sentinel-laravel
