<?php

declare(strict_types=1);

namespace Timeular\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Timeular\Api\AuthApi;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuthApi $authApi
    ) {
    }

    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ('developer/sign-in' === $request->getUri()->getPath()) {
            return $handler->handle($request);
        }

        if ('' !== $request->getHeaderLine('Authorization')) {
            return $handler->handle($request);
        }

        $request = $request->withHeader('Authorization', sprintf('Bearer %s', $this->authApi->signIn()));

        return $handler->handle($request);
    }
}
