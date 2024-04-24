<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class MissingContentTypeHeaderException extends \InvalidArgumentException
{
    private function __construct()
    {
        parent::__construct('Missing "Content-Type" header.');
    }

    public static function create(): self
    {
        return new self();
    }
}
