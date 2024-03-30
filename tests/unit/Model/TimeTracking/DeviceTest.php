<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Model\TimeTracking;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\Model\TimeTracking\Device;

class DeviceTest extends TestCase
{
    #[Test]
    public function it_creates_device_from_array():void
    {
        $device = Device::fromArray(
            [
                'serial' => 'QWER1234',
                'name' => 'Personal Tracker',
                'active' => $active = (bool) rand(0, 1),
                'disabled' => $disabled = (bool) rand(0, 1),
            ]
        );

        self::assertEquals('QWER1234', $device->serial);
        self::assertEquals('Personal Tracker', $device->name);
        self::assertEquals($active, $device->active);
        self::assertEquals($disabled, $device->disabled);
    }

    #[Test]
    public function it_creates_device_from_array_with_empty_name():void
    {
        $device = Device::fromArray(
            [
                'serial' => 'QWER1234',
                'name' => null,
                'active' => (bool) rand(0, 1),
                'disabled' => (bool) rand(0, 1),
            ]
        );

        self::assertNull($device->name);

        $device = Device::fromArray(
            [
                'serial' => 'QWER1234',
                'active' => (bool) rand(0, 1),
                'disabled' => (bool) rand(0, 1),
            ]
        );

        self::assertNull($device->name);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "Device" object.', $key));

        Device::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'serial' => 'QWER1234',
            'name' => 'Personal Tracker',
            'active' => (bool) rand(0, 1),
            'disabled' => (bool) rand(0, 1),
        ];

        $device = Device::fromArray($data);

        self::assertSame($device->toArray(), $data);
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['serial', 'active', 'disabled'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
