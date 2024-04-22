<?php

declare(strict_types=1);

namespace Timeular\Http\Serializer;

class Serializer implements SerializerInterface
{
    /** @param EncoderInterface[] $encoders */
    public function __construct(private array $encoders)
    {
    }

    public function serialize(mixed $data, string $format): string
    {
        $encoder = $this->encoders[$format] ?? null;

        if ($encoder === null) {
            throw MissingEncoderException::createForFormat($format);
        }

        return $encoder->encode($data);
    }

    public function deserialize(string $data, string $format): mixed
    {
        $encoder = $this->encoders[$format] ?? null;

        if ($encoder === null) {
            throw MissingEncoderException::createForFormat($format);
        }

        return $encoder->decode($data);
    }
}
