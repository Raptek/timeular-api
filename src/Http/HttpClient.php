<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Timeular\Http\Exception\AccessDeniedException;
use Timeular\Http\Exception\BadRequestException;
use Timeular\Http\Exception\HttpException;
use Timeular\Http\Exception\NotFoundException;
use Timeular\Http\Exception\UnauthorizedException;
use Timeular\Http\Serializer\SerializerInterface;

class HttpClient implements HttpClientInterface
{
    public function __construct(
        private string $baseUri,
        private string $apiKey,
        private string $apiSecret,
        private ClientInterface $httpClient,
        private RequestFactoryInterface $httpRequestFactory,
        private SerializerInterface $serializer,
        private MediaTypeResolverInterface $mediaTypeResolver,
    ) {
    }

    public function request(string $method, string $uri, array $payload = [], array $headers = []): mixed
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
            $request->getBody()->write($this->serializer->serialize($payload, $this->mediaTypeResolver->getMediaTypeFromMessage($request)));
        }

        return $request;
    }

    private function handleResponse(ResponseInterface $response): mixed
    {
        $statusCode = $response->getStatusCode();

        if (401 === $statusCode) {
            throw UnauthorizedException::create();
        }

        $body = $response->getBody()->getContents();

        if ('' === $body) {
            return [];
        }

        $response = $this->serializer->deserialize($body, $this->mediaTypeResolver->getMediaTypeFromMessage($response));

        if (200 !== $statusCode) {
            throw match ($statusCode) {
                400 => BadRequestException::withMessage($response['message']),
                403 => AccessDeniedException::withMessage($response['message']),
                404 => NotFoundException::withMessage($response['message']),
                default => new HttpException($response['message'], $statusCode),
            };
        }

        return $response;
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
