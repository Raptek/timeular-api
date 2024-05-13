<?php

declare(strict_types=1);

namespace Timeular\Factory;

use Timeular\Http\MediaTypeResolver;
use Timeular\Http\MediaTypeResolverInterface;

readonly class MediaTypeResolverFactory implements MediaTypeResolverFactoryInterface
{
    public function create(): MediaTypeResolverInterface
    {
        return new MediaTypeResolver();
    }
}
