<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class HttpException extends \DomainException
{
    final private function __construct(string $message, int $code, \Throwable|null $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(string $message, int $code, \Throwable|null $previous = null): static
    {
        return new static($message, $code, $previous);
    }
}
