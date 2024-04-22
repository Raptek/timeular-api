<?php

declare(strict_types=1);

namespace Timeular\Http\Serializer;

class MissingEncoderException extends \DomainException
{
    private function __construct(string $format)
    {
        parent::__construct(sprintf('Encoder for format "%s" does not exist', $format));
    }

    public static function createForFormat(string $format): self
    {
        return new self($format);
    }
}
