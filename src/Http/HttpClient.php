<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Timeular\Serializer\SerializerInterface;

class HttpClient implements HttpClientInterface
{
    public function __construct(
        private string $baseUri,
        private string $apiKey,
        private string $apiSecret,
        private ClientInterface $httpClient,
        private RequestFactoryInterface $httpRequestFactory,
        private SerializerInterface $serializer,
    ) {
    }

    public function request(string $method, string $uri, array $payload = [], array $headers = []): array
    {
        $request = $this->createRequest($method, $uri, $payload);

        foreach ($headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        $request = $this->handleAuthorization($request);

        return $this->handleResponse($this->httpClient->sendRequest($request));
    }

    private function createRequest(string $method, string $uri, array $payload = []): RequestInterface
    {
        $request = ($this->httpRequestFactory->createRequest(strtoupper($method), sprintf('%s/%s', $this->baseUri, ltrim($uri, '/'))))
            ->withHeader('Content-Type', 'application/json');

        if ([] !== $payload) {
            $request->getBody()->write($this->serializer->serialize($payload));
        }

        return $request;
    }

    private function handleResponse(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();

        if ('' === $body) {
            return [];
        }

        return $this->serializer->deserialize($body);
    }

    private function handleAuthorization(RequestInterface $request): RequestInterface
    {
        if ('' !== $request->getHeaderLine('Authorization') || true === str_ends_with($request->getUri()->getPath(), 'developer/sign-in')) {
            return $request;
        }

        $authRequest = $this->createRequest('POST', 'developer/sign-in', [
            'apiKey' => $this->apiKey,
            'apiSecret' => $this->apiSecret,
        ]);

        $token = $this->handleResponse($this->httpClient->sendRequest($authRequest))['token'];

        return $request->withHeader('Authorization', sprintf('Bearer %s', $token));
    }
}
