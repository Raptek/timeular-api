<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Webhooks\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Timeular\Exception\MissingArrayKeyException;
use Timeular\Webhooks\Model\Subscription;

#[CoversClass(Subscription::class)]
#[UsesClass(MissingArrayKeyException::class)]
class SubscriptionTest extends TestCase
{
    #[Test]
    public function it_creates_subscription_from_array(): void
    {
        $subscription = Subscription::fromArray(
            [
                'id' => '123456',
                'event' => 'trackingStarted',
                'target_url' => 'https://example.org/some-endpoint',
            ]
        );

        self::assertEquals('123456', $subscription->id);
        self::assertEquals('trackingStarted', $subscription->event->value);
        self::assertEquals('https://example.org/some-endpoint', $subscription->targetUrl);
    }

    #[Test]
    #[DataProvider('missingKeyData')]
    public function it_throws_exception_on_missing_array_key(array $data, string $key): void
    {
        self::expectException(MissingArrayKeyException::class);
        self::expectExceptionMessage(sprintf('Missing "%s" key for "Subscription" object.', $key));

        Subscription::fromArray($data);
    }

    #[Test]
    public function it_converts_to_array(): void
    {
        $data = [
            'id' => '123456',
            'event' => 'trackingStarted',
            'target_url' => 'https://example.org/some-endpoint',
        ];

        $subscription = Subscription::fromArray($data);

        self::assertEquals($data, $subscription->toArray());
    }

    public static function missingKeyData(): \Generator
    {
        $fields = ['id', 'event', 'target_url'];

        foreach ($fields as $field) {
            yield sprintf('Missing "%s" key', $field) => [array_fill_keys(array_diff($fields, [$field]), 'test'), $field];
        }
    }
}
