<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class AccessDeniedException extends HttpException
{
    private function __construct(string $message)
    {
        parent::__construct($message, 403);
    }

    public static function withMessage(string $message = 'Access denied'): self
    {
        return new self($message);
    }
}
