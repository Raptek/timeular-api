<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\TimeTracking\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\TimeTracking\Model\ActiveTimeEntry;
use Timeular\TimeTracking\Model\Duration;
use Timeular\TimeTracking\Model\Note;

#[CoversClass(ActiveTimeEntry::class)]
class ActiveTimeEntryTest extends TestCase
{
    #[Test]
    public function it_creates_active_time_entry_from_array():void
    {
        $startedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);

        $activeTimeEntry = ActiveTimeEntry::fromArray(
            [
                'id' => 34714420,
                'activityId' => '1217348',
                'startedAt' => $startedAt,
                'note' => [],
            ]
        );

        self::assertEquals(34714420, $activeTimeEntry->id);
        self::assertEquals('1217348', $activeTimeEntry->activityId);
        self::assertEquals(new \DateTimeImmutable($startedAt), $activeTimeEntry->startedAt);
        self::assertEquals(Note::fromArray([]), $activeTimeEntry->note);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "ActiveTimeEntry" object.', $key));

        ActiveTimeEntry::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $startedAt = (new \DateTimeImmutable())->format(Duration::FORMAT);

        $data = [
            'id' => 34714420,
            'activityId' => '1217348',
            'startedAt' => $startedAt,
            'note' => Note::fromArray([])->toArray(),
        ];

        $activeTimeEntry = ActiveTimeEntry::fromArray($data);

        self::assertSame($activeTimeEntry->toArray(), $data);
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['id', 'activityId', 'startedAt', 'note'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
