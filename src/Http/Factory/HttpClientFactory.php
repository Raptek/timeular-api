<?php

declare(strict_types=1);

namespace Timeular\Http\Factory;

use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Timeular\Http\HttpClient;
use Timeular\Http\HttpClientInterface;

readonly class HttpClientFactory implements HttpClientFactoryInterface
{
    public function __construct(
        private string $apiKey,
        private string $apiSecret,
        private PsrClientInterface $httpClient,
        private RequestFactoryFactoryInterface $requestFactoryFactory,
        private ResponseHandlerFactoryInterface $responseHandlerFactory = new ResponseHandlerFactory(),
    ) {}

    public function create(): HttpClientInterface
    {
        return new HttpClient(
            $this->apiKey,
            $this->apiSecret,
            $this->httpClient,
            $this->responseHandlerFactory->create(),
            $this->requestFactoryFactory->create(),
        );
    }
}
