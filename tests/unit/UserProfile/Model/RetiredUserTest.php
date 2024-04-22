<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\UserProfile\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\UserProfile\Model\RetiredUser;

class RetiredUserTest extends TestCase
{
    #[Test]
    public function it_creates_retired_user_from_array():void
    {
        $retiredUser = RetiredUser::fromArray(
            [
                'id' => '1',
                'name' => 'my name',
            ]
        );

        self::assertEquals('1', $retiredUser->id);
        self::assertEquals('my name', $retiredUser->name);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "RetiredUser" object.', $key));

        RetiredUser::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'id' => '1',
            'name' => 'my name',
        ];

        $user = RetiredUser::fromArray($data);

        self::assertSame($user->toArray(), $data);
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['id', 'name'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
