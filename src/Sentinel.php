<?php

namespace Kiwilan\Sentinel;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Kiwilan\Sentinel\Log\LogHandler;
use Throwable;

class Sentinel
{
    public function __construct(
        protected ?string $token = null,
        protected ?string $host = null,
        protected bool $enabled = false,
        protected int $status = 0,
        protected array $payload = [],
        protected string $message = 'Unknown error',
        protected ?LogHandler $error = null,
        protected ?string $user = null,
        protected string $method = 'stream', // curl, stream, guzzle
    ) {
    }

    public static function make(): self
    {
        $baseURL = SentinelConfig::host();
        $token = SentinelConfig::token();
        $self = new self(
            token: $token,
            host: "{$baseURL}/api/logs",
            enabled: SentinelConfig::enabled(),
        );
        // dump(env('SENTINEL_TOKEN'));
        // dump(SentinelConfig::token());
        // dump($self);

        return $self;
    }

    public function register(Throwable $e): array|false
    {
        if (! $this->enabled) {
            return false;
        }

        $this->error = LogHandler::make($e);
        $this->user = $this->setUser();

        if ($this->token === null) {
            throw new Exception('Sentinel token is not set');
        }

        $this->payload = $this->setPayload();

        return $this->send();
    }

    public function token(): string
    {
        return $this->token;
    }

    public function host(): string
    {
        return $this->host;
    }

    public function enabled(): bool
    {
        return $this->enabled;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function error(): ?LogHandler
    {
        return $this->error;
    }

    public function user(): ?string
    {
        return $this->user;
    }

    public function toArray(): array
    {
        return [
            'enabled' => $this->enabled(),
            'token' => $this->token(),
            'host' => $this->host(),
            'status' => $this->status(),
            'message' => $this->message(),
        ];
    }

    private function send(): array
    {
        if ($this->method === 'curl') {
            return $this->postWithCurl();
        }

        if ($this->method === 'stream') {
            return $this->postWithStream();
        }

        return $this->postWithGuzzle();
    }

    private function postWithGuzzle(): array
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->post($this->host, [
            'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => $this->payload,
        ]);

        $this->status = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $this->message = $body;

        if ($this->status !== 201 && $this->status !== 200 && $this->status !== 0) {
            throw new Exception("Sentinel error {$this->status}: {$this->message}");
        }

        return [
            'headers' => $response->getHeaders(),
            'status' => $this->status,
            'body' => $body,
            'json' => json_decode($body, true),
        ];
    }

    private function postWithStream(): array
    {
        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n".
                 "Accept: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($this->payload),
            ],
        ];

        $context = stream_context_create($options);
        $res = file_get_contents($this->host, false, $context);

        $status = $http_response_header[0];
        $this->status = (int) substr($status, 9, 3);
        $this->message = $res;

        if ($res === false) {
            throw new Exception("Sentinel error {$this->status}: {$this->message}");
        }

        return [
            'headers' => [],
            'status' => $this->status,
            'body' => $res,
            'json' => json_decode($res, true),
        ];
    }

    private function postWithCurl(): array
    {
        $content = json_encode($this->payload);

        $curl = curl_init($this->host);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-type: application/json',
            'Accept: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json = curl_exec($curl);

        $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $headers = curl_getinfo($curl, CURLINFO_HEADER_OUT);
        $message = json_decode($json, true);
        $this->message = $message['message'] ?? $json;

        if ($this->status !== 201 && $this->status !== 200 && $this->status !== 0) {
            throw new Exception("Sentinel error {$this->status}: {$this->message}");
        }

        curl_close($curl);
        $json = json_decode($json, true);

        return [
            'headers' => $headers,
            'status' => $this->status,
            'message' => $this->message,
            'json' => $json,
        ];
    }

    private function setUser(): ?string
    {
        $user = null;

        if (auth()->check()) {
            $id = auth()->user()->getAuthIdentifierName();

            /** @var Model */
            $auth = auth()->user();
            $user = $auth->toArray()[$id] ?? null;

            if ($user) {
                $user = "{$id}: {$user}";
            }
        }

        return $user;
    }

    private function setPayload(): array
    {
        return [
            'token' => $this->token,
            'app' => $this->error->app(),
            'env' => $this->error->env(),
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'is_auth' => auth()->check(),
            'user' => $this->user,
            'is_production' => $this->error->isProduction(),
            'url' => $this->error->url(),
            'method' => $this->error->method(),
            'user_agent' => $this->error->userAgent(),
            'ip' => $this->error->ip(),
            'base_path' => $this->error->basePath(),
            'date_time' => now(config('app.timezone'))->toDateTimeString(),
            'timezone' => config('app.timezone'),
            'current' => $this->error->current()?->toArray(),
        ];
    }
}
