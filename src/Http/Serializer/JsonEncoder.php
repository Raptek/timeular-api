<?php

declare(strict_types=1);

namespace Timeular\Http\Serializer;

class JsonEncoder implements EncoderInterface
{
    public function encode(mixed $data): string
    {
        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw SerializeException::create($exception);
        }
    }

    public function decode(string $data): array
    {
        try {
            $decoded = json_decode($data, true, flags: JSON_THROW_ON_ERROR);

            if (null === $decoded) {
                return [];
            }

            return $decoded;
        } catch (\JsonException $exception) {
            throw DeserializeException::create($exception);
        }
    }
}
