<?php

namespace App\Exceptions;

use Throwable;

class LogHandler
{
    protected function __construct(
        protected string $app,
        protected string $env,
        protected bool $isProduction,
        protected string $url,
        protected string $method,
        protected string $userAgent,
        protected string $ip,
        protected ?LogMessage $current = null,
        protected ?LogMessage $previous = null,
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

class LogMessage
{
    protected function __construct(
        protected int $code,
        protected string $file,
        protected int $line,
        protected string $message,
        protected array $trace,
        protected string $traceString,
    ) {
    }

    public static function make(?Throwable $throwable): ?self
    {
        if (! $throwable) {
            return null;
        }

        return new self(
            $throwable->getCode(),
            $throwable->getFile(),
            $throwable->getLine(),
            $throwable->getMessage(),
            $throwable->getTrace(),
            $throwable->getTraceAsString(),
        );
    }

    public function code(): int
    {
        return $this->code;
    }

    public function file(): string
    {
        return $this->file;
    }

    public function line(): int
    {
        return $this->line;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function trace(): array
    {
        return $this->trace;
    }

    public function traceString(): string
    {
        return $this->traceString;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code(),
            'file' => $this->file(),
            'line' => $this->line(),
            'message' => $this->message(),
            'trace' => $this->trace(),
            'trace_string' => $this->traceString(),
        ];
    }
}
