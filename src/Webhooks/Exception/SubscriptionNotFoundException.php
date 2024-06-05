<?php

declare(strict_types=1);

namespace Timeular\Webhooks\Exception;

class SubscriptionNotFoundException extends \Exception implements WebhooksException
{
    private function __construct(string $id)
    {
        parent::__construct(sprintf('Subscription with id "%s" could not be found.', $id));
    }

    public static function fromId(string $id): self
    {
        return new self($id);
    }
}
