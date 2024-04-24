<?php

declare(strict_types=1);

namespace Timeular\Http\Serializer;

class PassthroughEncoder implements EncoderInterface
{

    public function encode(mixed $data): string
    {
        throw EncodingNotSupportedException::create();
    }

    public function decode(string $data): string
    {
        return $data;
    }
}
