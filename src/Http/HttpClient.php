<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Timeular\Http\Middleware\MiddlewareInterface;
use Timeular\Http\Middleware\RequestHandlerStackInterface;
use Timeular\Serializer\SerializerInterface;

class HttpClient implements HttpClientInterface
{
    public function __construct(
        private string $baseUri,
        private RequestFactoryInterface $httpRequestFactory,
        private SerializerInterface $serializer,
        private RequestHandlerStackInterface $handler,
    ) {
    }

    public function setMiddlewares(MiddlewareInterface ...$middlewares): void
    {
        foreach ($middlewares as $middleware) {
            $this->handler->add($middleware);
        }
    }

    public function request(string $method, string $uri, array $payload = [], array $headers = []): array
    {
        $request = ($this->httpRequestFactory->createRequest(strtoupper($method), sprintf('%s/%s', $this->baseUri, ltrim($uri, '/'))))
            ->withHeader('Content-Type', 'application/json')
        ;

        if ([] !== $payload) {
            $request->getBody()->write($this->serializer->serialize($payload));
        }

        foreach ($headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        $response = $this->handler->handle($request);

        return $this->handleResponse($response);
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
