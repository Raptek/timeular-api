<?php

declare(strict_types=1);

namespace Timeular\Webhooks\Exception;

use Timeular\Webhooks\Model\Event;

class InvalidEventException extends \Exception implements WebhooksException
{
    private function __construct(string $event)
    {
        parent::__construct(sprintf('Event "%s" is not valid. Supported events are: %s', $event, join(', ', array_map(static fn(Event $event): string => $event->value, Event::cases()))));
    }

    public static function fromEvent(string $event): self
    {
        return new self($event);
    }
}
