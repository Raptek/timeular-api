<?php

declare(strict_types=1);

namespace Timeular\Http;

interface HttpClientInterface
{
    public function request(string $method, string $uri, array $payload = []): string|array;
}
