<?php

declare(strict_types=1);

namespace Timeular\Model\TimeTracking;

use Timeular\Exception\MissingArrayKeyException;

readonly class Device
{
    private function __construct(
        public string $serial,
        public string|null $name,
        public bool $active,
        public bool $disabled,
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('serial', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Device', 'serial');
        }

        if (false === array_key_exists('active', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Device', 'active');
        }

        if (false === array_key_exists('disabled', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Device', 'disabled');
        }

        return new self($data['serial'], $data['name'] ?? null, $data['active'], $data['disabled']);
    }

    public function toArray(): array
    {
        return [
            'serial' => $this->serial,
            'name' => $this->name,
            'active' => $this->active,
            'disabled' => $this->disabled,
        ];
    }
}
