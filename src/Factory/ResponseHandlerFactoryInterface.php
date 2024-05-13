<?php

declare(strict_types=1);

namespace Timeular\Factory;

use Timeular\Http\ResponseHandlerInterface;

interface ResponseHandlerFactoryInterface
{
    public function create(): ResponseHandlerInterface;
}
