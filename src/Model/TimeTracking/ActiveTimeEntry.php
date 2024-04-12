<?php

declare(strict_types=1);

namespace Timeular\Model\TimeTracking;

use Timeular\Exception\MissingArrayKeyException;

readonly class ActiveTimeEntry
{
    private function __construct(
        public int $id,
        public string $activityId,
        public \DateTimeInterface $startedAt,
        public Note $note,
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('id', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('ActiveTimeEntry', 'id');
        }

        if (false === array_key_exists('activityId', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('ActiveTimeEntry', 'activityId');
        }

        if (false === array_key_exists('startedAt', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('ActiveTimeEntry', 'startedAt');
        }

        if (false === array_key_exists('note', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('ActiveTimeEntry', 'note');
        }

        return new self($data['id'], $data['activityId'], new \DateTimeImmutable($data['startedAt']), Note::fromArray($data['note']));
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'activityId' => $this->activityId,
            'startedAt' => $this->startedAt->format(Duration::FORMAT),
            'note' => $this->note->toArray(),
        ];
    }
}
