<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class AccessDeniedException extends HttpException
{
    public static function withMessage(string $message = 'Access denied.'): self
    {
        return self::create($message, 403);
    }
}
