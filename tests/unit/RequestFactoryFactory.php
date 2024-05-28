<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular;

use Http\Discovery\Psr17FactoryDiscovery;
use Timeular\Http\Factory\RequestFactoryFactoryInterface;
use Timeular\Http\Factory\SerializerFactory;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;

class RequestFactoryFactory implements RequestFactoryFactoryInterface
{
    public function create(): RequestFactoryInterface
    {
        return new RequestFactory(
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
            (new SerializerFactory())->create(),
        );
    }
}
