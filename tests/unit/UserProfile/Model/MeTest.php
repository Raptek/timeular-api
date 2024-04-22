<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\UserProfile\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\UserProfile\Model\Me;

class MeTest extends TestCase
{
    #[Test]
    public function it_creates_user_from_array():void
    {
        $user = Me::fromArray(
            [
                'userId' => '1',
                'name' => 'my name',
                'email' => 'my-name@example.com',
                'defaultSpaceId' => '1',
            ]
        );

        self::assertEquals('1', $user->userId);
        self::assertEquals('my name', $user->name);
        self::assertEquals('my-name@example.com', $user->email);
        self::assertEquals('1', $user->defaultSpaceId);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "User" object.', $key));

        Me::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'userId' => '1',
            'name' => 'my name',
            'email' => 'my-name@example.com',
            'defaultSpaceId' => '1',
        ];

        $user = Me::fromArray($data);

        self::assertSame($user->toArray(), $data);
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['userId', 'name', 'email', 'defaultSpaceId'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
