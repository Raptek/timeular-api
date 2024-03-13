<?php

declare(strict_types=1);

namespace Timeular\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HandlerStack implements RequestHandlerStackInterface
{
    private array $middlewares = [];

    public function __construct(
        private MiddlewareInterface $fallbackMiddleware,
    ) {
    }

    public function add(MiddlewareInterface $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    public function handle(RequestInterface $request): ResponseInterface
    {
        if ([] === $this->middlewares) {
            return $this->fallbackMiddleware->process($request, $this);
        }

        $middleware = array_shift($this->middlewares);

        return $middleware->process($request, $this);
    }
}
