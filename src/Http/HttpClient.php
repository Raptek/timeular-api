<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
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
        private ResponseHandlerInterface $responseHandler,
    ) {
    }

    public function request(string $method, string $uri, array $payload = []): mixed
    {
        $request = $this->createRequest($method, $uri, $payload);
        $request = $this->handleAuthorization($request);
        $response = $this->httpClient->sendRequest($request);

        return $this->responseHandler->handle($response);
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

    private function handleAuthorization(RequestInterface $request): RequestInterface
    {
        if ('' !== $request->getHeaderLine('Authorization') || true === str_ends_with($request->getUri()->getPath(), 'developer/sign-in')) {
            return $request;
        }

        $authRequest = $this->createRequest('POST', 'developer/sign-in', [
            'apiKey' => $this->apiKey,
            'apiSecret' => $this->apiSecret,
        ]);

        $response = $this->httpClient->sendRequest($authRequest);
        $data = $this->responseHandler->handle($response);

        return $request->withHeader('Authorization', sprintf('Bearer %s', $data['token']));
    }
}
