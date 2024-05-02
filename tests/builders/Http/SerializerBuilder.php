<?php

declare(strict_types=1);

namespace Tests\Builders\Timeular\Http;

use Tests\Builders\Timeular\BuilderInterface;
use Timeular\Http\Serializer\EncoderInterface;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\PassthroughEncoder;
use Timeular\Http\Serializer\Serializer;
use Timeular\Http\Serializer\SerializerInterface;

class SerializerBuilder implements BuilderInterface
{
    private array $dependencies = [
        'encoders' => [],
    ];

    public function withEncoder(string $mediaType, EncoderInterface $encoder): self
    {
        $this->dependencies['encoders'][$mediaType] = $encoder;

        return $this;
    }

    private function defaults(): void
    {
        if (false === array_key_exists('application/json', $this->dependencies['encoders'])) {
            $this->withEncoder('application/json', new JsonEncoder());
        }

        if (false === array_key_exists('text/csv', $this->dependencies['encoders'])) {
            $this->withEncoder('text/csv', new PassthroughEncoder());
        }

        if (false === array_key_exists('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $this->dependencies['encoders'])) {
            $this->withEncoder('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', new PassthroughEncoder());
        }
    }

    public function build(): SerializerInterface
    {
        $this->defaults();

        return new Serializer($this->dependencies['encoders']);
    }
}
