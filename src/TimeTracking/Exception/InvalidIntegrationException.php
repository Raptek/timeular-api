<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Exception;

class InvalidIntegrationException extends \Exception implements ActivitiesException
{
    private function __construct(string $integration)
    {
        parent::__construct(sprintf('Provided integration "%s" is invalid. It must match this regular expression: ^[a-zA-Z0-9-_\.]{1,50}$', $integration));
    }

    public static function fromIntegration(string $integration): self
    {
        return new self($integration);
    }
}
