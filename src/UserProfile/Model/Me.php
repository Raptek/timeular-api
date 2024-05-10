<?php

declare(strict_types=1);

namespace Timeular\UserProfile\Model;

use Timeular\Exception\MissingArrayKeyException;

readonly class Me
{
    private function __construct(
        public string $userId,
        public string $name,
        public string $email,
        public string $defaultSpaceId,
    ) {}

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('userId', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('User', 'userId');
        }

        if (false === array_key_exists('name', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('User', 'name');
        }

        if (false === array_key_exists('email', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('User', 'email');
        }

        if (false === array_key_exists('defaultSpaceId', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('User', 'defaultSpaceId');
        }

        return new self($data['userId'], $data['name'], $data['email'], $data['defaultSpaceId']);
    }

    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
            'defaultSpaceId' => $this->defaultSpaceId,
        ];
    }
}
