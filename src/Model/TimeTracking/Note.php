<?php

declare(strict_types=1);

namespace Timeular\Model\TimeTracking;

use Timeular\Exception\MissingArrayKeyException;

readonly class Note
{
    private function __construct(
        public string $text,
        public array $tags = [],
        public array $mentions = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('text', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Note', 'text');
        }

        $tags = [];

        if (true === array_key_exists('tags', $data)) {
            try {
                $tags = array_map(static fn (array $tag): Tag => Tag::fromArray($tag), $data['tags']);
            } catch (\DomainException $exception) {
                // @todo throw nice exception
            }
        }

        $mentions = [];

        if (true === array_key_exists('mentions', $data)) {
            try {
                $mentions = array_map(static fn (array $mention): Mention => Mention::fromArray($mention), $data['mentions']);
            } catch (\DomainException $exception) {
                // @todo throw nice exception
            }
        }

        return new self($data['text'], $tags, $mentions);
    }
}
