<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Exception;

class NotSpaceAdminException extends \Exception implements ActivitiesException
{
    private function __construct()
    {
        parent::__construct('Only space admins are allowed to create activities.');
    }

    public static function create(): self
    {
        return new self();
    }
}
