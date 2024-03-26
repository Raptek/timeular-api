<?php

declare(strict_types=1);

namespace Timeular\Exception;

class MissingArrayKeyException extends \DomainException
{
    private function __construct(string $objectName, string $key)
    {
        parent::__construct(sprintf('Missing "%s" key for "%s" object.', $key, $objectName));
    }

    public static function forObjectAndKey(string $objectName, string $key): self
    {
        return new self($objectName, $key);
    }
}
