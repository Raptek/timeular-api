<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\TimeTracking\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\TimeTracking\Model\Activity;

#[CoversClass(Activity::class)]
class ActivityTest extends TestCase
{
    #[Test]
    public function it_creates_activity_from_array():void
    {
        $activity = Activity::fromArray(
            [
                'id' => '1',
                'name' => 'sleeping',
                'color' => '#a1b2c3',
                'integration' => 'zei',
                'spaceId' => '1',
                'deviceSide' => null,
            ]
        );

        self::assertEquals('1', $activity->id);
        self::assertEquals('sleeping', $activity->name);
        self::assertEquals('#a1b2c3', $activity->color);
        self::assertEquals('zei', $activity->integration);
        self::assertEquals('1', $activity->spaceId);
        self::assertNull($activity->deviceSide);
    }

    #[Test]
    public function it_creates_activity_from_array_with_empty_device_side():void
    {
        $device = Activity::fromArray(
            [
                'id' => '1',
                'name' => 'sleeping',
                'color' => '#a1b2c3',
                'integration' => 'zei',
                'spaceId' => '1',
                'deviceSide' => null,
            ]
        );

        self::assertNull($device->deviceSide);

        $device = Activity::fromArray(
            [
                'id' => '1',
                'name' => 'sleeping',
                'color' => '#a1b2c3',
                'integration' => 'zei',
                'spaceId' => '1',
            ]
        );

        self::assertNull($device->deviceSide);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "Activity" object.', $key));

        Activity::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'id' => '1',
            'name' => 'sleeping',
            'color' => '#a1b2c3',
            'integration' => 'zei',
            'spaceId' => '1',
            'deviceSide' => null,
        ];

        $activity = Activity::fromArray($data);

        self::assertSame($activity->toArray(), $data);
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['id', 'name', 'color', 'integration', 'spaceId'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
