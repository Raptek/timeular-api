<?php

declare(strict_types=1);

namespace Timeular\Http\Serializer;

class EncodingNotSupportedException extends \DomainException
{
    private function __construct()
    {
        parent::__construct('Encoding is not supported');
    }

    public static function create(): self
    {
        return new self();
    }
}
