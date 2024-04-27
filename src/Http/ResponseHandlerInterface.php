<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Message\ResponseInterface;

interface ResponseHandlerInterface
{
    public function handle(ResponseInterface $response): string|array;
}
