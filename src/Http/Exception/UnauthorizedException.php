<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class UnauthorizedException extends HttpException
{
    private function __construct(string $message)
    {
        parent::__construct($message, 401);
    }

    public static function withMessage(string $message = 'Unauthorized'): self
    {
        return new self($message);
    }
}
