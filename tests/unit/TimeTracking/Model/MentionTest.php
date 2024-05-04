<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\TimeTracking\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\TimeTracking\Model\Mention;

#[CoversClass(Mention::class)]
class MentionTest extends TestCase
{
    #[Test]
    public function it_creates_mention_from_array():void
    {
        $mention = Mention::fromArray(
            [
                'id' => 1,
                'key' => '1234',
                'label' => 'some mention',
                'scope' => 'timeular',
                'spaceId' => '1',
            ]
        );

        self::assertEquals(1, $mention->id);
        self::assertEquals('1234', $mention->key);
        self::assertEquals('some mention', $mention->label);
        self::assertEquals('timeular', $mention->scope);
        self::assertEquals('1', $mention->spaceId);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "Mention" object.', $key));

        Mention::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'id' => 1,
            'key' => '1234',
            'label' => 'some-mention',
            'scope' => 'timeular',
            'spaceId' => '1',
        ];

        $mention = Mention::fromArray($data);

        self::assertSame($mention->toArray(), $data);
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['id', 'key', 'label', 'scope', 'spaceId'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
