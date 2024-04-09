<?php

declare(strict_types=1);

namespace Timeular\Model\TimeTracking;

use Timeular\Exception\MissingArrayKeyException;

readonly class Duration
{
    private function __construct(
        public \DateTimeInterface $startedAt,
        public \DateTimeInterface $stoppedAt,
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('startedAt', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Duration', 'startedAt');
        }

        if (false === array_key_exists('stoppedAt', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Duration', 'stoppedAt');
        }

        return new self(new \DateTimeImmutable($data['startedAt']), new \DateTimeImmutable($data['stoppedAt']));
    }
}
