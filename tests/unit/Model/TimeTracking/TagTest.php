<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Model\TimeTracking;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\Model\TimeTracking\Tag;

class TagTest extends TestCase
{
    #[Test]
    public function it_creates_tag_from_array():void
    {
        $tag = Tag::fromArray(
            [
                'id' => 1,
                'key' => '1234',
                'label' => 'some-tag',
                'scope' => 'timeular',
                'spaceId' => '1',
            ]
        );

        self::assertEquals(1, $tag->id);
        self::assertEquals('1234', $tag->key);
        self::assertEquals('some-tag', $tag->label);
        self::assertEquals('timeular', $tag->scope);
        self::assertEquals('1', $tag->spaceId);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "Tag" object.', $key));

        Tag::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'id' => 1,
            'key' => '1234',
            'label' => 'some-tag',
            'scope' => 'timeular',
            'spaceId' => '1',
        ];

        $tag = Tag::fromArray($data);

        self::assertSame($tag->toArray(), $data);
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['id', 'key', 'label', 'scope', 'spaceId'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
