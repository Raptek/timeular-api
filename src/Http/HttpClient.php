<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class HttpClient implements HttpClientInterface
{
    public function __construct(
        private string $apiKey,
        private string $apiSecret,
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private ResponseHandlerInterface $responseHandler,
    ) {
    }

    public function request(string $method, string $uri, array $payload = []): mixed
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

        $authRequest = $this->requestFactory->create('POST', 'developer/sign-in', [
            'apiKey' => $this->apiKey,
            'apiSecret' => $this->apiSecret,
        ]);

        $response = $this->httpClient->sendRequest($authRequest);
        $data = $this->responseHandler->handle($response);

        return $request->withHeader('Authorization', sprintf('Bearer %s', $data['token']));
    }
}
