<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class BadRequestException extends HttpException
{
    public static function withMessage(string $message = 'Bad request.'): self
    {
        return self::create($message, 400);
    }
}
