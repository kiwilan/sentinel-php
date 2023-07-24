<?php

namespace Kiwilan\Sentinel;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Kiwilan\Sentinel\Log\LogHandler;
use Throwable;

class Sentinel
{
    protected function __construct(
        readonly protected ?string $token,
        readonly protected string $host,
        readonly protected bool $enabled,
        protected int $status,
        protected array $data,
        protected string $message,
    ) {
    }

    public static function make(Throwable $e, string $token = null): array|false
    {
        $baseURL = SentinelConfig::host();
        $self = new self(
            token: $token ?? config('sentinel.token'),
            host: "{$baseURL}/api/logs",
            enabled: SentinelConfig::enabled(),
            status: 0,
            data: [],
            message: '',
        );

        if (! $self->enabled) {
            return false;
        }

        $error = LogHandler::make($e);
        $user = $self->user();

        $data = $self->json($error, $user);
        $res = $self->send($data);

        return $res;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function enabled(): bool
    {
        return $this->enabled;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function host(): string
    {
        return $this->host;
    }

    public function toArray(): array
    {
        return [
            'enabled' => $this->enabled(),
            'token' => $this->token(),
            'host' => $this->host(),
            'status' => $this->status(),
            'data' => $this->data(),
            'message' => $this->message(),
        ];
    }

    private function send(array $data): array
    {

        $this->data = $data;
        $content = json_encode($data);

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
        $message = json_decode($json, true);
        $this->message = $message['message'] ?? $json;

        if ($this->status !== 201 && $this->status !== 200 && $this->status !== 0) {
            throw new Exception("Sentinel error {$this->status}: {$this->message}");
        }

        curl_close($curl);

        return json_decode($json, true);

    }

    private function user(): ?string
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

    private function json(LogHandler $error, ?string $user): array
    {
        return [
            'token' => $this->token,
            'app' => $error->app(),
            'env' => $error->env(),
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'is_auth' => auth()->check(),
            'user' => $user,
            'is_production' => $error->isProduction(),
            'url' => $error->url(),
            'method' => $error->method(),
            'user_agent' => $error->userAgent(),
            'ip' => $error->ip(),
            'base_path' => $error->basePath(),
            'date_time' => now(config('app.timezone'))->toDateTimeString(),
            'timezone' => config('app.timezone'),
            'current' => $error->current()?->toArray(),
        ];
    }
}
