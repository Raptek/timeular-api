<?php

declare(strict_types=1);

namespace Timeular\Serializer;

class JsonSerializer implements SerializerInterface
{
    public function serialize(mixed $data): string
    {
        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new SerializeException(
                sprintf('Unable to serialize: %s', $exception->getMessage()),
                $exception->getCode(),
                $exception,
            );
        }
    }

    public function deserialize(string $data): ?array
    {
        try {
            return json_decode($data, true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new DeserializeException(
                sprintf('Unable to deserialize: %s', $exception->getMessage()),
                $exception->getCode(),
                $exception,
            );
        }
    }
}
