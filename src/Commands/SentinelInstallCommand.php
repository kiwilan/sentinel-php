<?php

namespace Kiwilan\Sentinel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SentinelInstallCommand extends Command
{
    public $signature = 'sentinel:install {--host=} {--token=} {--handler=} {--dotenv=}';

    public $description = 'Install Sentinel package
                            {--host : URL of Sentinel application}
                            {--token : Token available in Sentinel for your application}
                            {--handler : Handler path, default: `app/Exceptions/Handler.php`}
                            {--dotenv : Path to .env file, default: `.env`}';

    public function __construct(
        public string $dotenvPath = '.env',
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $host = $this->option('host');
        $token = $this->option('token');
        $handler = $this->option('handler');
        $dotenv = $this->option('dotenv');

        Artisan::call('vendor:publish', [
            '--tag' => 'sentinel-laravel-config',
        ]);

        $this->info('Sentinel config published');

        if (! $host) {
            $host = $this->ask('What is your Sentinel URL?');
        }

        if (! $token) {
            $token = $this->ask('What is your application token?');
        }

        $this->dotenvPath = $this->dotenv($dotenv);
        $this->addToDotEnv('SENTINEL_ENABLED=', 'true');
        $this->addToDotEnv('SENTINEL_HOST=', $host);
        $this->addToDotEnv('SENTINEL_TOKEN=', $token);

        $this->info('.env updated');

        $this->updateHandler($handler);

        $this->info('Handler updated');

        $this->comment('All done');

        return self::SUCCESS;
    }

    private function updateHandler(string $handler = null)
    {
        $path = $handler;
        if (! $handler) {
            $path = base_path('app/Exceptions/Handler.php');
        }

        if (! file_exists($path)) {
            $this->error('Handler not found');

            return;
        }

        $handler = file_get_contents($path);

        if (str_contains($handler, 'Sentinel::make')) {
            return;
        }

        $handler = str_replace(
            '$this->reportable(function (Throwable $e) {',
            "\$this->reportable(function (Throwable \$e) {\n\t\t\t\\Kiwilan\\Sentinel\\Facades\\Sentinel::register(\$e);",
            $handler
        );

        file_put_contents($path, $handler);
    }

    private function addToDotEnv(string $key, mixed $value)
    {

        $dotenv = file_get_contents($this->dotenvPath);

        if (str_contains($dotenv, $key)) {
            // delete the existing token
            $dotenv = preg_replace('/'.$key.'([^\n]+)/', $key, $dotenv);
            $dotenv = preg_replace('/'.$key.'/', $key.$value, $dotenv);
            file_put_contents($this->dotenvPath, $dotenv);
        } else {
            // add the token
            $dotenv .= "\n".$key.$value;
            file_put_contents($this->dotenvPath, $dotenv);
        }
    }

    private function dotenv(string $path = null): ?string
    {
        if (! $path) {
            $path = base_path('.env');
        }

        if (! file_exists($path)) {
            $root = getcwd();
            $path = $root.'/.env';
        }

        if (! file_exists($path)) {
            $this->error('.env not found');

            return null;
        }

        return $path;
    }
}
