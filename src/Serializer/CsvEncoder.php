<?php

declare(strict_types=1);

namespace Timeular\Serializer;

class CsvEncoder implements EncoderInterface
{
    public function encode(mixed $data): string
    {
        // TODO: Implement encode() method.
    }

    public function decode(string $data): mixed
    {
        return $data;
    }
}
