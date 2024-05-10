<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Model;

use Timeular\Exception\MissingArrayKeyException;

readonly class TimeEntry
{
    private function __construct(
        public string $id,
        public string $activityId,
        public Duration $duration,
        public Note $note,
    ) {}

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('id', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('TimeEntry', 'id');
        }

        if (false === array_key_exists('activityId', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('TimeEntry', 'activityId');
        }

        if (false === array_key_exists('duration', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('TimeEntry', 'duration');
        }

        if (false === array_key_exists('note', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('TimeEntry', 'note');
        }

        return new self(
            $data['id'],
            $data['activityId'],
            Duration::fromArray($data['duration']),
            Note::fromArray($data['note']),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'activityId' => $this->activityId,
            'duration' => $this->duration->toArray(),
            'note' => $this->note->toArray(),
        ];
    }
}
