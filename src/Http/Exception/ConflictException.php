<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class ConflictException extends HttpException
{
    public static function withMessage(string $message = 'Conflict.'): self
    {
        return self::create($message, 409);
    }
}
