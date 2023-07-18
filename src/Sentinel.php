<?php

namespace Kiwilan\Sentinel;

use App\Exceptions\LogHandler;
use Throwable;

class Sentinel
{
    public static function make(Throwable $e)
    {
        // $msg = [
        //     '**Sentinel for '.config('app.name').'**',
        //     '*'.date('Y-m-d H:i:s').'*',
        //     '`'.request()->fullUrl().'`'.' (`'.request()->method().'` method)',
        //     '```bash',
        //     $e->getMessage().' (error code: '.$e->getCode().')',
        //     'In file: '.$e->getFile().' (line: '.$e->getLine().')',
        //     '```',
        // ];

        // if (app()->environment('production')) {
        //     NotifyService::make()
        //         ->message(implode("\n", $msg))
        //         ->send()
        //     ;
        // }

        if (! SentinelConfig::enabled()) {
            return;
        }

        $client = new \GuzzleHttp\Client();
        $baseURL = SentinelConfig::host();
        $api = "{$baseURL}/api/reports";

        $error = LogHandler::make($e);

        $res = $client->post($api, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'http_errors' => false,
            'json' => [
                'token' => SentinelConfig::token(),
                'app' => $error->app(),
                'env' => $error->env(),
                'is_production' => $error->isProduction(),
                'url' => $error->url(),
                'method' => $error->method(),
                'user_agent' => $error->userAgent(),
                'ip' => $error->ip(),
                'date_time' => now(config('app.timezone', 'UTC'))->toDateTimeString(),
                'current' => $error->current()?->toArray(),
                'previous' => $error->previous()?->toArray(),
            ],
        ]);

        if ($res->getStatusCode() !== 200) {
            $body = $res->getBody()->getContents();
            $response = json_decode($body, true);
            $message = $response['message'] ?? $body;

            throw new \Exception("Sentinel error: {$message}");
        }
    }
}
