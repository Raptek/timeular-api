<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular;

use PsrMock\Psr17\RequestFactory as PsrRequestFactory;
use PsrMock\Psr17\StreamFactory as PsrStreamFactory;
use Timeular\Http\Factory\RequestFactoryFactoryInterface;
use Timeular\Http\Factory\SerializerFactory;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;

class RequestFactoryFactory implements RequestFactoryFactoryInterface
{
    public function create(): RequestFactoryInterface
    {
        return new RequestFactory(
            new PsrRequestFactory(),
            new PsrStreamFactory(),
            (new SerializerFactory())->create(),
        );
    }
}
