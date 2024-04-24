<?php

declare(strict_types=1);

namespace Timeular\Http\Exception;

class MultipleContentTypeValuesException extends \InvalidArgumentException
{
    private function __construct()
    {
        parent::__construct('Using multiple "Content-Type" headers is not supported.');
    }

    public static function create(): self
    {
        return new self();
    }
}
