<?php

namespace Timeular\Http;

use Psr\Http\Message\RequestInterface;

interface RequestFactoryInterface
{
    public function create(string $method, string $uri): RequestInterface;
}
