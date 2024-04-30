<?php

declare(strict_types=1);

namespace Timeular\Builders\Http;

use Timeular\Http\Serializer\EncoderInterface;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\PassthroughEncoder;
use Timeular\Http\Serializer\Serializer;
use Timeular\Http\Serializer\SerializerInterface;

class SerializerBuilder
{
    private array $encoders = [];

    public function withEncoder(string $mediaType, EncoderInterface $encoder): self
    {
        $this->encoders[$mediaType] = $encoder;

        return $this;
    }

    public function defaults(): self
    {
        return $this->withEncoder('application/json', new JsonEncoder())
            ->withEncoder('text/csv', new PassthroughEncoder())
            ->withEncoder('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', new PassthroughEncoder())
            ;
    }

    public function getSerializer(): SerializerInterface
    {
        return new Serializer($this->encoders);
    }
}
