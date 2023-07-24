<?php

namespace Kiwilan\Sentinel\Commands;

use Exception;
use Illuminate\Console\Command;
use Kiwilan\Sentinel\Sentinel;

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

        if (! $host) {
            $this->error('Host is not defined');
            throw new \Exception('Host is not defined');
        }

        if (! $token) {
            $this->error('Token is not defined');
            throw new \Exception('Token is not defined');
        }

        $this->info("Host: {$host}");
        $this->info("Token: {$token}");

        $this->info('Testing connection to Sentinel...');
        $response = Sentinel::make(new Exception('Test exception', 500), $token);

        $valid = $response['message'] ?? null;

        if ($valid === 'success') {
            $this->comment('All done');

            return self::SUCCESS;
        }

        $json = json_encode($response, JSON_PRETTY_PRINT);
        $this->error("Something went wrong, here is the response: {$json}");

        return self::FAILURE;
    }
}
