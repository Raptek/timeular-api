<?php

namespace Timeular\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RequestHandlerInterface
{
    public function handle(RequestInterface $request): ResponseInterface;
}
