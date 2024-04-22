<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\TimeTracking\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\TimeTracking\Model\Duration;
use Timeular\TimeTracking\Model\Note;
use Timeular\TimeTracking\Model\TimeEntry;

class TimeEntryTest extends TestCase
{
    #[Test]
    public function it_creates_time_entry_from_array():void
    {
        $startedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);
        $stoppedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);

        $duration = Duration::fromArray(
            [
                'startedAt' => $startedAt,
                'stoppedAt' => $stoppedAt,
            ]
        );

        $timeEntry = TimeEntry::fromArray(
            [
                'id' => '34714420',
                'activityId' => '1217348',
                'duration' => [
                    'startedAt' => $startedAt,
                    'stoppedAt' => $stoppedAt,
                ],
                'note' => [],
            ]
        );

        self::assertEquals('34714420', $timeEntry->id);
        self::assertEquals('1217348', $timeEntry->activityId);
        self::assertEquals($duration, $timeEntry->duration);
        self::assertEquals(Note::fromArray([]), $timeEntry->note);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "TimeEntry" object.', $key));

        TimeEntry::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $startedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);
        $stoppedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);

        $duration = Duration::fromArray(
            [
                'startedAt' => $startedAt,
                'stoppedAt' => $stoppedAt,
            ]
        );

        $data = [
            'id' => '34714420',
            'activityId' => '1217348',
            'duration' => $duration->toArray(),
            'note' => Note::fromArray([])->toArray(),
        ];

        $timeEntry = TimeEntry::fromArray($data);

        self::assertSame($timeEntry->toArray(), $data);
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['id', 'activityId', 'duration', 'note'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
