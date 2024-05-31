<?php

declare(strict_types=1);

namespace Timeular\Http\Factory;

use Composer\InstalledVersions;
use Http\Discovery\Psr17FactoryDiscovery;

readonly class DiscoverableRequestFactoryFactory extends RequestFactoryFactory
{
    public function __construct(
        SerializerFactoryInterface $serializerFactory = new SerializerFactory(),
    ) {
        if (false === InstalledVersions::isInstalled('php-http/discovery')) {
            throw new \Exception('In order to use PSR implementations discovery, "php-http/discovery" must be installed');
        }

        parent::__construct(
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
            $serializerFactory,
        );
    }
}
