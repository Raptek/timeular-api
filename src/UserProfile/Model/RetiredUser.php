<?php

declare(strict_types=1);

namespace Timeular\UserProfile\Model;

use Timeular\Exception\MissingArrayKeyException;

readonly class RetiredUser
{
    private function __construct(
        public string $id,
        public string $name,
    ) {}

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('id', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('RetiredUser', 'id');
        }

        if (false === array_key_exists('name', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('RetiredUser', 'name');
        }

        return new self($data['id'], $data['name']);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
