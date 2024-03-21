<?php

declare(strict_types=1);

namespace Timeular\Http\RequestModifier;

use Psr\Http\Message\RequestInterface;

class CompositeModifier implements RequestModifierInterface
{
    private array $modifiers;

    public function __construct(
        RequestModifierInterface ...$modifiers,
    ) {
        $this->modifiers = $modifiers;
    }

    public function enrich(RequestInterface $request): RequestInterface
    {
        foreach ($this->modifiers as $modifier) {
            $request = $modifier->enrich($request);
        }

        return $request;
    }
}
