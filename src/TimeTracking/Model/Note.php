<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Model;

readonly class Note
{
    private function __construct(
        public string|null $text,
        public array $tags = [],
        public array $mentions = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $text = array_key_exists('text', $data) ? $data['text'] : null;

        $tags = [];

        if (true === array_key_exists('tags', $data)) {
            try {
                $tags = array_map(static fn(array $tag): Tag => Tag::fromArray($tag), $data['tags']);
            } catch (\DomainException $exception) {
                // @todo throw nice exception
            }
        }

        $mentions = [];

        if (true === array_key_exists('mentions', $data)) {
            try {
                $mentions = array_map(static fn(array $mention): Mention => Mention::fromArray($mention), $data['mentions']);
            } catch (\DomainException $exception) {
                // @todo throw nice exception
            }
        }

        return new self($text, $tags, $mentions);
    }

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'tags' => array_map(static fn(Tag $tag): array => $tag->toArray(), $this->tags),
            'mentions' => array_map(static fn(Mention $mention): array => $mention->toArray(), $this->mentions),
        ];
    }
}
