<?php

declare(strict_types=1);

namespace Timeular\Http;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private ClientInterface $httpClient;
    private RequestFactoryInterface $httpRequestFactory;

    public function __construct(
        private string $baseUri,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $httpRequestFactory = null,
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->httpRequestFactory = $httpRequestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
    }

    public function get(string $uri, array $payload): ResponseInterface
    {
        $request = ($this->httpRequestFactory->createRequest('GET', sprintf('%s/%s', $this->baseUri, ltrim($uri, '/'))))
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(json_encode($payload));

        return $this->httpClient->sendRequest($request);
    }

    public function post(string $uri, array $payload): ResponseInterface
    {
        $request = ($this->httpRequestFactory->createRequest('POST', sprintf('%s/%s', $this->baseUri, ltrim($uri, '/'))))
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(json_encode($payload));

        return $this->httpClient->sendRequest($request);
    }
}
