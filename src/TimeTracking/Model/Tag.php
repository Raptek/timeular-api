<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Model;

use Timeular\Exception\MissingArrayKeyException;

readonly class Tag
{
    private function __construct(
        public int $id,
        public string $key,
        public string $label,
        public string $scope,
        public string $spaceId,
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('id', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Tag', 'id');
        }

        if (false === array_key_exists('key', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Tag', 'key');
        }

        if (false === array_key_exists('label', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Tag', 'label');
        }

        if (false === array_key_exists('scope', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Tag', 'scope');
        }

        if (false === array_key_exists('spaceId', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Tag', 'spaceId');
        }

        return new self($data['id'], $data['key'], $data['label'], $data['scope'], $data['spaceId']);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'label' => $this->label,
            'scope' => $this->scope,
            'spaceId' => $this->spaceId,
        ];
    }
}
