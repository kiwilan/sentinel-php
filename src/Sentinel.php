<?php

namespace Kiwilan\Sentinel;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Kiwilan\Sentinel\Log\LogHandler;
use Throwable;

class Sentinel
{
    protected function __construct(
        protected ?string $token = null,
        protected ?string $host = null,
        protected bool $enabled = false,
        protected int $status = 0,
        protected array $payload = [],
        protected string $message = 'Unknown error',
        protected ?LogHandler $error = null,
        protected ?string $user = null,
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

        return $self;
    }

    public function register(Throwable $e): array|false
    {
        if (! $this->enabled) {
            return false;
        }

        $this->error = LogHandler::make($e);
        $this->user = $this->setUser();

        if (! $this->token) {
            throw new Exception('Sentinel token is not set');
        }

        $this->payload = $this->setPayload();

        return $this->send();
    }

    public function token(): ?string
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
        $options = [
            'http' => [
                'header' => "Content-Type: application/json\r\n".
                 "Accept: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($this->payload),
                'ignore_errors' => true,
            ],
        ];

        $context = stream_context_create($options);
        $body = file_get_contents($this->host, false, $context);

        $status = $http_response_header[0];
        $this->status = (int) substr($status, 9, 3);
        $this->message = $body;

        if ($body === false) {
            throw new Exception("Sentinel error {$this->status}: {$this->message}");
        }

        $json = json_decode($body, true);
        $message = $json['message'] ?? null;

        if ($this->status !== 200) {
            throw new Exception("Sentinel error {$this->status}: {$this->message}");
        }

        return [
            'headers' => [],
            'status' => $this->status,
            'body' => $body,
            'json' => $json,
            'isValid' => $message === 'success',
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
