<?php

declare(strict_types=1);

namespace Timeular\Http\Factory;

use Timeular\Http\Serializer\SerializerInterface;

interface SerializerFactoryInterface
{
    public function create(): SerializerInterface;
}
