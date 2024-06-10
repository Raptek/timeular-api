<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Exception;

class ThirdPartyIntegrationException extends \Exception implements ActivitiesException
{
    private function __construct()
    {
        parent::__construct('Third Party Integrations are not allowed in shared spaces - please use your personal space.');
    }

    public static function create(): self
    {
        return new self();
    }
}
