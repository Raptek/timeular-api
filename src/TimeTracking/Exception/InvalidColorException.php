<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Exception;

class InvalidColorException extends \Exception implements ActivitiesException
{
    private function __construct(string $color)
    {
        parent::__construct(sprintf('Provided color "%s" is invalid. It must be a valid hexadecimal color representation.', $color));
    }

    public static function fromColor(string $color): self
    {
        return new self($color);
    }
}
