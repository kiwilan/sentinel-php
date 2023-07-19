<?php

namespace Kiwilan\Sentinel\Commands;

use Illuminate\Console\Command;

class SentinelTestCommand extends Command
{
    public $signature = 'sentinel:test';

    public $description = 'Test Sentinel installation';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
