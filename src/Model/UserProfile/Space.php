<?php

declare(strict_types=1);

namespace Timeular\Model\UserProfile;

use Timeular\Exception\MissingArrayKeyException;

readonly class Space
{
    private function __construct(
        public string $id,
        public string $name,
        public bool $default,
        public array $members,
        public array $retiredMembers,
    ) {
    }

    public static function fromArray(array $data): self
    {
        if (false === array_key_exists('id', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Space', 'id');
        }

        if (false === array_key_exists('name', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Space', 'name');
        }

        if (false === array_key_exists('default', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Space', 'default');
        }

        if (false === array_key_exists('members', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Space', 'members');
        }

        if (false === array_key_exists('retiredMembers', $data)) {
            throw MissingArrayKeyException::forObjectAndKey('Space', 'retiredMembers');
        }

        $members = array_map(static fn (array $memberData): User => User::fromArray($memberData), $data['members']);
        $retiredMembers = array_map(static fn (array $memberData): RetiredUser => RetiredUser::fromArray($memberData), $data['retiredMembers']);

        return new self($data['id'], $data['name'], $data['default'], $members, $retiredMembers);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'default' => $this->default,
            'members' => array_map(static fn (User $user): array => $user->toArray(), $this->members),
            'retiredMembers' => array_map(static fn (RetiredUser $retiredUser): array => $retiredUser->toArray(), $this->retiredMembers),
        ];
    }
}
