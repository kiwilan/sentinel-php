<?php

namespace Kiwilan\Sentinel\Log;

use Throwable;

class LogMessage
{
    protected function __construct(
        readonly protected int $code,
        readonly protected string $file,
        readonly protected int $line,
        readonly protected string $message,
        readonly protected array $trace,
        readonly protected string $traceString,
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
