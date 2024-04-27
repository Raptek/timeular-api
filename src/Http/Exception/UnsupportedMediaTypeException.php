<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class UnsupportedMediaTypeException extends HttpException
{
    public static function fromMediaType(string $mediaType): self
    {
        return self::create(sprintf('Media Type "%s" is not supported.', $mediaType), 415);
    }
}
