<?php

namespace Timeular\Serializer;

interface SerializerInterface
{
    public function serialize(mixed $data): string;

    public function deserialize(string $data): mixed;
}
