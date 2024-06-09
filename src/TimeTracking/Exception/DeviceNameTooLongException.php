<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Exception;

class DeviceNameTooLongException extends \Exception implements DevicesException
{
    private function __construct(string $name)
    {
        parent::__construct(sprintf('Provided Device name "%s" is too long. Allowed length is 50 characters, %d provided.', $name, mb_strlen($name)));
    }

    public static function fromName(string $name): self
    {
        return new self($name);
    }
}
