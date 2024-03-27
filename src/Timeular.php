<?php

declare(strict_types=1);

namespace Timeular;

use Timeular\Api\TimeTracking;
use Timeular\Api\TimeularApi;
use Timeular\Model\Device;
use Timeular\Model\User;

class Timeular
{
    public function __construct(
        private TimeularApi $api,
        private TimeTracking\DevicesApi $devices,
    ) {
    }

    public function me(): User
    {
        return $this->api->me();
    }

    public function devices(): array
    {
        return $this->devices->list();
    }

    public function activateDevice(string $serial): Device
    {
        return $this->devices->activate($serial);
    }

    public function deactivateDevice(string $serial): Device
    {
        return $this->devices->deactivate($serial);
    }

    public function forgetDevice(string $serial): void
    {
        $this->devices->forget($serial);
    }

    public function disableDevice(string $serial): Device
    {
        return $this->devices->disable($serial);
    }

    public function enableDevice(string $serial): Device
    {
        return $this->devices->enable($serial);
    }

    public function editDevice(string $serial, string $name): Device
    {
        return $this->devices->edit($serial, $name);
    }
}
