<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\TimeTracking\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\TimeTracking\Model\Duration;

#[CoversClass(Duration::class)]
class DurationTest extends TestCase
{
    #[Test]
    public function it_creates_duration_from_array():void
    {
        $startedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);
        $stoppedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);

        $duration = Duration::fromArray(
            [
                'startedAt' => $startedAt,
                'stoppedAt' => $stoppedAt,
            ]
        );

        self::assertEquals(new \DateTimeImmutable($startedAt), $duration->startedAt);
        self::assertEquals(new \DateTimeImmutable($stoppedAt), $duration->stoppedAt);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "Duration" object.', $key));

        Duration::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $startedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);
        $stoppedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);

        $data = [
            'startedAt' => $startedAt,
            'stoppedAt' => $stoppedAt,
        ];

        $duration = Duration::fromArray($data);

        self::assertSame($duration->toArray(), $data);
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['startedAt', 'stoppedAt'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
