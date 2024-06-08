<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Exception;

class DeviceNotFoundException extends \Exception implements DevicesException
{
    private function __construct(string $serial)
    {
        parent::__construct(sprintf('Device of serial "%s" is not known.', $serial));
    }

    public static function fromSerial(string $serial): self
    {
        return new self($serial);
    }
}
