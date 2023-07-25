<?php

namespace Kiwilan\Sentinel\Commands;

use Exception;
use Illuminate\Console\Command;
use Kiwilan\Sentinel\Facades\Sentinel;

class SentinelTestCommand extends Command
{
    public $signature = 'sentinel:test {--host=} {--token=}';

    public $description = 'Test Sentinel installation
                            {--host : URL of Sentinel application}
                            {--token : Token available in Sentinel for your application}';

    public function handle(): int
    {
        $host = $this->option('host');
        $token = $this->option('token');

        if (! $host) {
            $host = config('sentinel.host');
        }

        if (! $token) {
            $token = config('sentinel.token');
        }

        if (! $host || ! $token) {
            $this->error('Host or token is not defined');

            return self::FAILURE;
        }

        $this->info("Host: {$host}");
        $this->info("Token: {$token}");

        $this->info('Testing connection to Sentinel...');
        $response = Sentinel::register(new Exception('Test exception'));

        if ($response['isValid']) {
            $this->comment('All done');

            return self::SUCCESS;
        }

        $json = json_encode($response, JSON_PRETTY_PRINT);
        $this->error("Something went wrong, here is the response: {$json}");

        return self::FAILURE;
    }
}
