<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Model\UserProfile;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\Model\UserProfile\RetiredUser;
use Timeular\Model\UserProfile\Role;
use Timeular\Model\UserProfile\Space;
use Timeular\Model\UserProfile\User;

class SpaceTest extends TestCase
{
    #[Test]
    public function it_creates_space_from_array():void
    {
        $member = [
            'id' => '1',
            'name' => 'my name',
            'email' => 'my-name@example.com',
            'role' => Role::Admin->value,
        ];
        $retiredMember = [
            'id' => '1',
            'name' => 'my name',
        ];
        $space = Space::fromArray(
            [
                'id' => '1',
                'name' => 'My Personal Space',
                'default' => $default = (bool)rand(0, 1),
                'members' => [
                    $member,
                ],
                'retiredMembers' => [
                    $retiredMember,
                ],
            ]
        );

        self::assertEquals('1', $space->id);
        self::assertEquals('My Personal Space', $space->name);
        self::assertEquals($default, $space->default);
        self::assertEquals([User::fromArray($member)], $space->members);
        self::assertEquals([RetiredUser::fromArray($retiredMember)], $space->retiredMembers);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "Space" object.', $key));

        Space::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $member = [
            'id' => '1',
            'name' => 'my name',
            'email' => 'my-name@example.com',
            'role' => Role::Admin->value,
        ];
        $retiredMember = [
            'id' => '1',
            'name' => 'my name',
        ];
        $data = [
            'id' => '1',
            'name' => 'My Personal Space',
            'default' => (bool)rand(0, 1),
            'members' => [
                $member,
            ],
            'retiredMembers' => [
                $retiredMember,
            ],
        ];

        $user = Space::fromArray($data);

        self::assertSame($user->toArray(), $data);
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['id', 'name', 'default', 'members', 'retiredMembers'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
