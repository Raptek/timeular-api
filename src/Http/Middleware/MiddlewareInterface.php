<?php

namespace Timeular\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface
{
    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
