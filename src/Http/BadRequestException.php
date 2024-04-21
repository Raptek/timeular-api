<?php

declare(strict_types=1);

namespace Timeular\Http;

class BadRequestException extends HttpException
{
    private function __construct(string $message)
    {
        parent::__construct($message, 400);
    }

    public static function withMessage(string $message = 'Bad request'): self
    {
        return new self($message);
    }
}
