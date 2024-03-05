<?php

declare(strict_types=1);

namespace Timeular\Http;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Timeular\Serializer\JsonSerializer;
use Timeular\Serializer\SerializerInterface;

class Client
{
    private ClientInterface $httpClient;
    private RequestFactoryInterface $httpRequestFactory;
    private SerializerInterface $serializer;

    public function __construct(
        private string $baseUri,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $httpRequestFactory = null,
        ?SerializerInterface $serializer = null,
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->httpRequestFactory = $httpRequestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->serializer = $serializer ?? new JsonSerializer();
    }

    public function request(string $method, string $uri, array $payload = [], array $headers = []): array
    {
        $request = ($this->httpRequestFactory->createRequest(strtoupper($method), sprintf('%s/%s', rtrim($this->baseUri, '/'), ltrim($uri, '/'))))
            ->withHeader('Content-Type', 'application/json')
        ;

        if ([] !== $payload) {
            $request->getBody()->write($this->serializer->serialize($payload));
        }

        foreach ($headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        return $this->handleResponse($this->httpClient->sendRequest($request));
    }

    private function handleResponse(ResponseInterface $response): array
    {
        return $this->serializer->deserialize($response->getBody()->getContents());
    }
}
