<?php

declare(strict_types=1);

namespace Timeular\Webhooks\Exception;

class MaximumSubscriptionsReachedException extends \Exception implements WebhooksException
{
    private function __construct(string $event)
    {
        parent::__construct(sprintf('Maximum number of subscriptions for event "%s" has been reached.', $event));
    }

    public static function fromEvent(string $event): self
    {
        return new self($event);
    }
}
