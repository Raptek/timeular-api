<?php

declare(strict_types=1);

namespace Timeular\Http\Serializer;

class SerializeException extends \RuntimeException
{
    private function __construct(\Throwable $previous)
    {
        parent::__construct(sprintf('Unable to serialize: %s', $previous->getMessage()), previous: $previous);
    }

    public static function create(\Throwable $throwable): self
    {
        return new self($throwable);
    }
}
