<?php

declare(strict_types=1);

namespace Timeular\Http\Factory;

use Timeular\Http\HttpClientInterface;

interface HttpClientFactoryInterface
{
    public function create(): HttpClientInterface;
}
