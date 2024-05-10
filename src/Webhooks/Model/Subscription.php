<?php

declare(strict_types=1);

namespace Timeular\Webhooks\Model;

use Timeular\Exception\MissingArrayKeyException;

readonly class Subscription
{
    private function __construct(
        public string $id,
        public Event $event,
        public string $targetUrl,
    ) {}

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('id', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Subscription', 'id');
        }

        if (false === array_key_exists('event', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Subscription', 'event');
        }

        if (false === array_key_exists('target_url', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Subscription', 'target_url');
        }

        return new self($data['id'], Event::from($data['event']), $data['target_url']);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'event' => $this->event->value,
            'target_url' => $this->targetUrl,
        ];
    }
}
