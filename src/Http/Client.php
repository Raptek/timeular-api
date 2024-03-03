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

    public function request(string $method, string $uri, array $payload = [], array $headers = []): ResponseInterface
    {
        $request = ($this->httpRequestFactory->createRequest(strtoupper($method), sprintf('%s/%s', rtrim($this->baseUri, '/'), ltrim($uri, '/'))))
            ->withHeader('Content-Type', 'application/json')
        ;

        if ([] !== $payload) {
            $request->getBody()->write(json_encode($payload));
        }

        foreach ($headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        return $this->httpClient->sendRequest($request);
    }
}
