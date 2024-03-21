<?php

declare(strict_types=1);

namespace Timeular\Http\RequestModifier;

use Psr\Http\Message\RequestInterface;
use Timeular\Api\AuthApi;

class AuthModifier implements RequestModifierInterface
{
    public function __construct(
        private AuthApi $authApi
    ) {
    }

    public function enrich(RequestInterface $request): RequestInterface
    {
        if ('developer/sign-in' === $request->getUri()->getPath()) {
            return $request;
        }

        if ('' !== $request->getHeaderLine('Authorization')) {
            return $request;
        }

        return $request->withHeader('Authorization', sprintf('Bearer %s', $this->authApi->signIn()));
    }
}
