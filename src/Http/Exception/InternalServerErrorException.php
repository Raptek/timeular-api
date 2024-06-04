<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class InternalServerErrorException extends HttpException
{
    public static function withMessage(string $message = 'Internal Server Error.'): self
    {
        return self::create($message, 500);
    }
}
