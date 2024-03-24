<?php

declare(strict_types=1);

namespace Timeular;

use Timeular\Api\TimeTracking;
use Timeular\Api\TimeularApi;

class Timeular
{
    public function __construct(
        private TimeularApi $api,
        private TimeTracking\DevicesApi $devices,
    ) {
    }

    public function me(): array
    {
        return $this->api->me();
    }

    public function devices(): array
    {
        return $this->devices->list();
    }

    public function activateDevice(string $id): array
    {
        return $this->devices->activate($id);
    }

    public function deactivateDevice(string $id): array
    {
        return $this->devices->deactivate($id);
    }
}
