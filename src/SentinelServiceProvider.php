<?php

namespace Kiwilan\Sentinel;

use Kiwilan\Sentinel\Commands\SentinelInstallCommand;
use Kiwilan\Sentinel\Commands\SentinelTestCommand;
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
            ->hasCommand(SentinelInstallCommand::class)
            ->hasCommand(SentinelTestCommand::class);
    }

    public function bootingPackage()
    {
        // $this->app->bind('sentinel-laravel', function () {
        //     return Sentinel::make();
        // });

        // $this->app->bind('sentinel-laravel', \Kiwilan\Sentinel\Sentinel::class);
        $this->app->bind('sentinel', fn () => \Kiwilan\Sentinel\Sentinel::make());
    }
}
