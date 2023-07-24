<?php

namespace Kiwilan\Sentinel\Commands;

use Illuminate\Console\Command;

class SentinelSendCommand extends Command
{
    public $signature = 'sentinel:send';

    public $description = 'Send Sentinel log';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
