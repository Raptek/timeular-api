<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Timeular\Auth\Api\AuthApi;

readonly class HttpClient implements HttpClientInterface
{
    public function __construct(
        private string $apiKey,
        private string $apiSecret,
        private ClientInterface $httpClient,
        private ResponseHandlerInterface $responseHandler,
        private RequestFactoryInterface $requestFactory,
    ) {}

    public function request(string $method, string $uri, array $payload = []): string|array
    {
        $request = $this->requestFactory->create($method, $uri, $payload);
        $request = $this->handleAuthorization($request);
        $response = $this->httpClient->sendRequest($request);

        return $this->responseHandler->handle($response);
    }

    private function handleAuthorization(RequestInterface $request): RequestInterface
    {
        if ('' !== $request->getHeaderLine('Authorization') || true === str_ends_with($request->getUri()->getPath(), 'developer/sign-in')) {
            return $request;
        }

        $authApi = new AuthApi($this);

        return $request->withHeader('Authorization', sprintf('Bearer %s', $authApi->signIn($this->apiKey, $this->apiSecret)));
    }
}
