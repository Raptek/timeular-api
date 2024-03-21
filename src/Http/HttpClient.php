<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Timeular\Http\RequestModifier\RequestModifierInterface;
use Timeular\Serializer\SerializerInterface;

class HttpClient implements HttpClientInterface
{
    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $httpRequestFactory,
        private SerializerInterface $serializer,
        private RequestModifierInterface $requestModifier,
    ) {
    }

    public function request(string $method, string $uri, array $payload = [], array $headers = []): array
    {
        $request = ($this->httpRequestFactory->createRequest(strtoupper($method), ltrim($uri, '/')))
            ->withHeader('Content-Type', 'application/json')
        ;

        if ([] !== $payload) {
            $request->getBody()->write($this->serializer->serialize($payload));
        }

        foreach ($headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        $request = $this->requestModifier->enrich($request);

        return $this->handleResponse($this->httpClient->sendRequest($request));
    }

    private function handleResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();

        if ('' === $body) {
            return [];
        }

        return $this->serializer->deserialize($body);
    }
}
