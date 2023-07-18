<?php

namespace Kiwilan\Sentinel;

use Kiwilan\Sentinel\Commands\SentinelCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SentinelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('sentinel')
            ->hasConfigFile()
            ->hasCommand(SentinelCommand::class);
    }
}
