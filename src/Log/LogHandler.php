<?php

namespace Kiwilan\Sentinel\Log;

use Throwable;

class LogHandler
{
    protected function __construct(
        readonly protected string $app,
        readonly protected string $env,
        readonly protected bool $isProduction,
        readonly protected string $url,
        readonly protected string $method,
        readonly protected string $userAgent,
        readonly protected string $ip,
        readonly protected ?LogMessage $current = null,
        readonly protected ?LogMessage $previous = null,
    ) {
    }

    public static function make(Throwable $throwable): self
    {
        return new self(
            config('app.name'),
            app()->environment(),
            app()->environment('production'),
            request()->fullUrl(),
            request()->method(),
            request()->userAgent(),
            request()->ip(),
            LogMessage::make($throwable),
            LogMessage::make($throwable->getPrevious()),
        );
    }

    public function app(): string
    {
        return $this->app;
    }

    public function env(): string
    {
        return $this->env;
    }

    public function isProduction(): bool
    {
        return $this->isProduction;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function userAgent(): string
    {
        return $this->userAgent;
    }

    public function ip(): string
    {
        return $this->ip;
    }

    public function current(): ?LogMessage
    {
        return $this->current;
    }

    public function previous(): ?LogMessage
    {
        return $this->previous;
    }
}
