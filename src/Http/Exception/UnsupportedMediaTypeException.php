<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class UnsupportedMediaTypeException extends HttpException
{
    private function __construct(string $message)
    {
        parent::__construct($message, 415);
    }

    public static function fromMediaType(string $mediaType): self
    {
        return new self(sprintf('Media Type "%s" is not supported.', $mediaType));
    }
}
