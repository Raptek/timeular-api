<?php

declare(strict_types=1);

namespace Timeular\Http\Middleware;

use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RequestMiddleware implements ClientInterface, MiddlewareInterface
{
    private ClientInterface $httpClient;

    public function __construct(
        ?ClientInterface $httpClient = null,
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }

    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->sendRequest($request);
    }
}
