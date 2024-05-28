<?php

declare(strict_types=1);

namespace Timeular\Http\Factory;

use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface as PsrStreamFactoryInterface;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;

readonly class RequestFactoryFactory implements RequestFactoryFactoryInterface
{
    public function __construct(
        private PsrRequestFactoryInterface $requestFactory,
        private PsrStreamFactoryInterface $streamFactory,
        private SerializerFactoryInterface $serializerFactory = new SerializerFactory(),
    ) {}

    public function create(): RequestFactoryInterface
    {
        return new RequestFactory(
            $this->requestFactory,
            $this->streamFactory,
            $this->serializerFactory->create(),
        );
    }
}
