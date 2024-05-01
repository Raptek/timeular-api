<?php

declare(strict_types=1);

namespace Timeular\Builders\Http;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\MediaTypeResolverInterface;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\Serializer\SerializerInterface;

class RequestFactoryBuilder
{
    private SerializerInterface|null $serializer = null;
    private PsrRequestFactoryInterface|null $psrRequestFactory = null;
    private MediaTypeResolverInterface|null $mediaTypeResolver = null;

    public function withSerializer(SerializerInterface $serializer): self
    {
        $this->serializer = $serializer;

        return $this;
    }

    public function withPsrRequestFactory(PsrRequestFactoryInterface $requestFactory): self
    {
        $this->psrRequestFactory = $requestFactory;

        return $this;
    }

    public function withMediaTypeResolver(MediaTypeResolverInterface $mediaTypeResolver): self
    {
        $this->mediaTypeResolver = $mediaTypeResolver;

        return $this;
    }

    public function defaults(): self
    {
        return $this->withSerializer((new SerializerBuilder())->defaults()->getSerializer())
            ->withPsrRequestFactory(Psr17FactoryDiscovery::findRequestFactory())
            ->withMediaTypeResolver(new MediaTypeResolver());
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        if (null === $this->serializer || null == $this->psrRequestFactory || null === $this->mediaTypeResolver) {
            throw new \InvalidArgumentException('Either use "defaults()" method or provide all required dependencies');
        }

        return new RequestFactory(
            $this->psrRequestFactory,
            $this->mediaTypeResolver,
            $this->serializer,
        );
    }
}
