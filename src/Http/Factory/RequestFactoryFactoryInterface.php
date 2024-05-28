<?php

declare(strict_types=1);

namespace Timeular\Http\Factory;

use Timeular\Http\RequestFactoryInterface;

interface RequestFactoryFactoryInterface
{
    public function create(): RequestFactoryInterface;
}
