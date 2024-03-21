<?php

declare(strict_types=1);

namespace Timeular\Http\RequestModifier;

use Psr\Http\Message\RequestInterface;

interface RequestModifierInterface
{
    public function enrich(RequestInterface $request): RequestInterface;
}
