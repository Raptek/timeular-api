<?php

declare(strict_types=1);

namespace Timeular;

use Timeular\Api\TimeularApi;

class Timeular
{
    public function __construct(
        private TimeularApi $api,
    ) {
    }

    public function me(): array
    {
        return $this->api->me();
    }

    public function devicesList(): array
    {
        return $this->api->devicesList();
    }
}
