<?php

namespace Timeular\Http;

use Psr\Http\Message\RequestInterface;

interface RequestFactoryInterface
{
    public const string BASE_URI = 'https://api.timeular.com/api/v3';

    public function create(string $method, string $uri): RequestInterface;
}
