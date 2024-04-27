<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class UnauthorizedException extends HttpException
{
    public static function withMessage(string $message = 'Unauthorized.'): self
    {
        return self::create($message, 401);
    }
}
