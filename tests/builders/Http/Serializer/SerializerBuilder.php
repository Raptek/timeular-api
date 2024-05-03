<?php

declare(strict_types=1);

namespace Tests\Builders\Timeular\Http\Serializer;

use Tests\Builders\Timeular\BuilderInterface;
use Timeular\Http\Serializer\EncoderInterface;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\PassthroughEncoder;
use Timeular\Http\Serializer\Serializer;
use Timeular\Http\Serializer\SerializerInterface;

class SerializerBuilder implements BuilderInterface
{
    private array $dependencies = [];

    public function withEncoder(string $mediaType, EncoderInterface $encoder): self
    {
        $this->dependencies[$mediaType] = $encoder;

        return $this;
    }

    private function defaults(): void
    {
        $this->dependencies['application/json'] ??= new JsonEncoder();
        $this->dependencies['text/csv'] ??= new PassthroughEncoder();
        $this->dependencies['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'] ??= new PassthroughEncoder();
    }

    public function build(): SerializerInterface
    {
        $this->defaults();

        return new Serializer($this->dependencies);
    }
}
