<?php

namespace Kiwilan\Sentinel\Commands;

use Illuminate\Console\Command;

class SentinelCommand extends Command
{
    public $signature = 'sentinel-php';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
