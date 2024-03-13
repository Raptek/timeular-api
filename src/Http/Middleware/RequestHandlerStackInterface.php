<?php

declare(strict_types=1);

namespace Timeular\Http\Middleware;

interface RequestHandlerStackInterface extends RequestHandlerInterface
{
    public function add(MiddlewareInterface $middleware): void;
}
