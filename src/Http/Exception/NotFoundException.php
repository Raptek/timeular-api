<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class NotFoundException extends HttpException
{
    public static function withMessage(string $message = 'Not found.'): self
    {
        return self::create($message, 404);
    }
}
