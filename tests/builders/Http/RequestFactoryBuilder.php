<?php

declare(strict_types=1);

namespace Tests\Builders\Timeular\Http;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Tests\Builders\Timeular\BuilderInterface;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\MediaTypeResolverInterface;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\Serializer\SerializerInterface;

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

    public function withMediaTypeResolver(MediaTypeResolverInterface $mediaTypeResolver): self
    {
        $this->dependencies['media-type-resolver'] = $mediaTypeResolver;

        return $this;
    }

    private function defaults(): void
    {
        if (false === array_key_exists('psr-request-factory', $this->dependencies)) {
            $this->withPsrRequestFactory(Psr17FactoryDiscovery::findRequestFactory());
        }

        if (false === array_key_exists('media-type-resolver', $this->dependencies)) {
            $this->withMediaTypeResolver(new MediaTypeResolver());
        }

        if (false === array_key_exists('serializer', $this->dependencies)) {
            $this->withSerializer((new SerializerBuilder())->build());
        }
    }

    public function build(): RequestFactoryInterface
    {
        $this->defaults();

        return new RequestFactory(
            $this->dependencies['psr-request-factory'],
            $this->dependencies['media-type-resolver'],
            $this->dependencies['serializer'],
        );
    }
}
