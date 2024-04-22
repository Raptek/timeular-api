<?php

declare(strict_types=1);

namespace Timeular\UserProfile\Model;

use Timeular\Exception\MissingArrayKeyException;

readonly class User
{
    private function __construct(
        public string $id,
        public string $name,
        public string $email,
        public Role $role,
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('id', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('User', 'id');
        }

        if (false === array_key_exists('name', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('User', 'name');
        }

        if (false === array_key_exists('email', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('User', 'email');
        }

        if (false === array_key_exists('role', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('User', 'role');
        }

        return new self($data['id'], $data['name'], $data['email'], Role::from($data['role']));
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role->value,
        ];
    }
}
