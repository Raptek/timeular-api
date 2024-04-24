<?php

namespace Timeular\Http\Serializer;

interface SerializerInterface
{
    public function serialize(mixed $data, string $format): string;

    public function deserialize(string $data, string $format): string|array;
}
