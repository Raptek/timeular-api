<?php

declare(strict_types=1);

namespace Timeular\Factory;

use Timeular\Http\MediaTypeResolverInterface;

interface MediaTypeResolverFactoryInterface
{
    public function create(): MediaTypeResolverInterface;
}
