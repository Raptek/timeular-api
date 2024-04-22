<?php

namespace Timeular\Http\Serializer;

interface EncoderInterface
{
    public function encode(mixed $data): string;

    public function decode(string $data): mixed;
}
