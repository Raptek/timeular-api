<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Timeular\Http\Middleware\MiddlewareInterface;
use Timeular\Http\Middleware\RequestHandlerStackInterface;

class MiddlewareAwareClient implements ClientInterface
{
    private iterable $middlewares;

    public function __construct(
        private RequestHandlerStackInterface $handler,
        MiddlewareInterface ...$middlewares
    ) {
        $this->middlewares = $middlewares;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        foreach ($this->middlewares as $middleware) {
            $this->handler->add($middleware);
        }

        return $this->handler->handle($request);
    }
}
