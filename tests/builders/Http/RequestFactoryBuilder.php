<?php

declare(strict_types=1);

namespace Tests\Builders\Timeular\Http;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Tests\Builders\Timeular\BuilderInterface;
use Tests\Builders\Timeular\Http\Serializer\SerializerBuilder;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\Serializer\SerializerInterface;
use Psr\Http\Message\StreamFactoryInterface as PsrStreamFactoryInterface;

class RequestFactoryBuilder implements BuilderInterface
{
    private array $dependencies = [];

    public function withSerializer(SerializerInterface $serializer): self
    {
        $this->dependencies['serializer'] = $serializer;

        return $this;
    }

    public function withPsrRequestFactory(PsrRequestFactoryInterface $requestFactory): self
    {
        $this->dependencies['psr-request-factory'] = $requestFactory;

        return $this;
    }

    public function withPsrStreamFactory(PsrStreamFactoryInterface $streamFactory): self
    {
        $this->dependencies['psr-stream-factory'] = $streamFactory;

        return $this;
    }

    private function defaults(): void
    {
        $this->dependencies['psr-request-factory'] ??= Psr17FactoryDiscovery::findRequestFactory();
        $this->dependencies['psr-stream-factory'] ??= Psr17FactoryDiscovery::findStreamFactory();
        $this->dependencies['serializer'] ??= (new SerializerBuilder())->build();
    }

    public function build(): RequestFactoryInterface
    {
        $this->defaults();

        return new RequestFactory(
            $this->dependencies['psr-request-factory'],
            $this->dependencies['psr-stream-factory'],
            $this->dependencies['serializer'],
        );
    }
}
