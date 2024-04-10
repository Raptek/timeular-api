<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Model\UserProfile;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\Model\UserProfile\Role;
use Timeular\Model\UserProfile\User;

class UserTest extends TestCase
{
    #[Test]
    public function it_creates_user_from_array():void
    {
        $user = User::fromArray(
            [
                'id' => '1',
                'name' => 'my name',
                'email' => 'my-name@example.com',
                'role' => Role::Admin->value,
            ]
        );

        self::assertEquals('1', $user->id);
        self::assertEquals('my name', $user->name);
        self::assertEquals('my-name@example.com', $user->email);
        self::assertEquals(Role::Admin, $user->role);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "User" object.', $key));

        User::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'id' => '1',
            'name' => 'my name',
            'email' => 'my-name@example.com',
            'role' => Role::Admin->value,
        ];

        $user = User::fromArray($data);

        self::assertSame($user->toArray(), $data);
    }


    public static function missingKeyData(): \Generator
    {
        $fields = ['id', 'name', 'email', 'role'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
