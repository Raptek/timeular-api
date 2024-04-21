<?php

declare(strict_types=1);

namespace Timeular\Http;

class NotFoundException extends HttpException
{
    private function __construct(string $message)
    {
        parent::__construct($message, 404);
    }

    public static function withMessage(string $message = 'Not found'): self
    {
        return new self($message);
    }
}
