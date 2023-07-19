<?php

namespace Kiwilan\Sentinel\Commands;

use Illuminate\Console\Command;

class SentinelInstallCommand extends Command
{
    public $signature = 'sentinel:install';

    public $description = 'Install Sentinel package';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
