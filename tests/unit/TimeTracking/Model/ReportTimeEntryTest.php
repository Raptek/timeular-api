<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\TimeTracking\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\TimeTracking\Model\Activity;
use Timeular\TimeTracking\Model\Duration;
use Timeular\TimeTracking\Model\Note;
use Timeular\TimeTracking\Model\ReportTimeEntry;

#[CoversClass(ReportTimeEntry::class)]
#[UsesClass(Activity::class)]
#[UsesClass(Duration::class)]
#[UsesClass(Note::class)]
#[UsesClass(MissingArrayKeyException::class)]
class ReportTimeEntryTest extends TestCase
{
    public static function missingKeyData(): \Generator
    {
        $fields = ['id', 'activity', 'duration', 'note'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }

    #[Test]
    public function it_creates_report_time_entry_from_array(): void
    {
        $startedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);
        $stoppedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);

        $activity = [
            'id' => '1',
            'name' => 'sleeping',
            'color' => '#a1b2c3',
            'integration' => 'zei',
            'spaceId' => '1',
        ];

        $duration = Duration::fromArray(
            [
                'startedAt' => $startedAt,
                'stoppedAt' => $stoppedAt,
            ],
        );

        $reportTimeEntry = ReportTimeEntry::fromArray(
            [
                'id' => '34714420',
                'activity' => $activity,
                'duration' => [
                    'startedAt' => $startedAt,
                    'stoppedAt' => $stoppedAt,
                ],
                'note' => [],
            ],
        );

        self::assertEquals('34714420', $reportTimeEntry->id);
        self::assertEquals(Activity::fromArray($activity), $reportTimeEntry->activity);
        self::assertEquals($duration, $reportTimeEntry->duration);
        self::assertEquals(Note::fromArray([]), $reportTimeEntry->note);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "ReportTimeEntry" object.', $key));

        ReportTimeEntry::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $startedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);
        $stoppedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);

        $activity = Activity::fromArray(
            [
                'id' => '1',
                'name' => 'sleeping',
                'color' => '#a1b2c3',
                'integration' => 'zei',
                'spaceId' => '1',
            ],
        );

        $duration = Duration::fromArray(
            [
                'startedAt' => $startedAt,
                'stoppedAt' => $stoppedAt,
            ],
        );

        $data = [
            'id' => '34714420',
            'activity' => $activity->toArray(),
            'duration' => $duration->toArray(),
            'note' => Note::fromArray([])->toArray(),
        ];

        $timeEntry = ReportTimeEntry::fromArray($data);

        self::assertSame($timeEntry->toArray(), $data);
    }
}
