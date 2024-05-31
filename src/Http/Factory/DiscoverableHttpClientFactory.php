<?php

declare(strict_types=1);

namespace Timeular\Http\Factory;

use Composer\InstalledVersions;
use Http\Discovery\Psr18ClientDiscovery;

readonly class DiscoverableHttpClientFactory extends HttpClientFactory
{
    public function __construct(
        string $apiKey,
        string $apiSecret,
        ResponseHandlerFactoryInterface $responseHandlerFactory = new ResponseHandlerFactory(),
    ) {
        if (false === InstalledVersions::isInstalled('php-http/discovery')) {
            throw new \Exception('In order to use PSR implementations discovery, "php-http/discovery" must be installed');
        }

        parent::__construct(
            $apiKey,
            $apiSecret,
            Psr18ClientDiscovery::find(),
            new DiscoverableRequestFactoryFactory(),
            $responseHandlerFactory,
        );
    }
}
