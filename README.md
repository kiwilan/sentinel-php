# Sentinel for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kiwilan/sentinel-php.svg?style=flat-square)](https://packagist.org/packages/kiwilan/sentinel-php)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/sentinel-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kiwilan/sentinel-php/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/sentinel-php/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/kiwilan/sentinel-php/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kiwilan/sentinel-php.svg?style=flat-square)](https://packagist.org/packages/kiwilan/sentinel-php)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require kiwilan/sentinel-php
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="sentinel-php-config"
```

This is the contents of the published config file:

```php
return [
  'enabled' => env('SENTINEL_ENABLED', false),
  'host' => env('SENTINEL_HOST', 'http://app.sentinel.test'),
  'token' => env('SENTINEL_TOKEN'),

  'notifications' => [
    'enabled' => env('SENTINEL_NOTIFICATIONS_ENABLED', false),
    'service' => env('SENTINEL_NOTIFICATION_SERVICE', 'discord'),
    'token' => env('SENTINEL_NOTIFICATION_TOKEN'),
  ],
];
```

## Usage

In `app/Exceptions/Handler.php`

```php
<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Kiwilan\Sentinel;

class Handler extends ExceptionHandler
{
  /**
   * Register the exception handling callbacks for the application.
   */
  public function register(): void
  {
    $this->reportable(function (Throwable $e) {
      Sentinel::handle($e);
    });
  }
}
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
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
