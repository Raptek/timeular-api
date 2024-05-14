<?php

declare(strict_types=1);

namespace Timeular\Http\Factory;

use Timeular\Http\ResponseHandler;
use Timeular\Http\ResponseHandlerInterface;

readonly class ResponseHandlerFactory implements ResponseHandlerFactoryInterface
{
    public function __construct(
        private MediaTypeResolverFactoryInterface $mediaTypeResolverFactory = new MediaTypeResolverFactory(),
        private SerializerFactoryInterface $serializerFactory = new SerializerFactory(),
    ) {}

    public function create(): ResponseHandlerInterface
    {
        return new ResponseHandler(
            $this->mediaTypeResolverFactory->create(),
            $this->serializerFactory->create(),
        );
    }
}
