<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Model;

use Timeular\Exception\MissingArrayKeyException;

readonly class ReportTimeEntry
{
    private function __construct(
        public string $id,
        public Activity $activity,
        public Duration $duration,
        public Note $note,
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('id', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('ReportTimeEntry', 'id');
        }

        if (false === array_key_exists('activity', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('ReportTimeEntry', 'activity');
        }

        if (false === array_key_exists('duration', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('ReportTimeEntry', 'duration');
        }

        if (false === array_key_exists('note', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('ReportTimeEntry', 'note');
        }

        return new self(
            $data['id'],
            Activity::fromArray($data['activity']),
            Duration::fromArray($data['duration']),
            Note::fromArray($data['note'])
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'activity' => $this->activity->toArray(),
            'duration' => $this->duration->toArray(),
            'note' => $this->note->toArray(),
        ];
    }
}
