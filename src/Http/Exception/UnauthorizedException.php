<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class UnauthorizedException extends HttpException
{
    private function __construct()
    {
        parent::__construct('Unauthorized.', 401);
    }

    public static function create(): self
    {
        return new self();
    }
}
