<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Model;

use Timeular\Exception\MissingArrayKeyException;

readonly class Activity
{
    private function __construct(
        public string $id,
        public string $name,
        public string $color,
        public string $integration,
        public string $spaceId,
        public int|null $deviceSide = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('id', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Activity', 'id');
        }

        if (false === array_key_exists('name', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Activity', 'name');
        }

        if (false === array_key_exists('color', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Activity', 'color');
        }

        if (false === array_key_exists('integration', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Activity', 'integration');
        }

        if (false === array_key_exists('spaceId', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Activity', 'spaceId');
        }

        return new self($data['id'], $data['name'], $data['color'], $data['integration'], $data['spaceId'], $data['deviceSide'] ?? null);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'integration' => $this->integration,
            'spaceId' => $this->spaceId,
            'deviceSide' => $this->deviceSide,
        ];
    }
}
