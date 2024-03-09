<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Serializer;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Serializer\DeserializeException;
use Timeular\Serializer\JsonSerializer;
use Timeular\Serializer\SerializeException;

class JsonSerializerTest extends TestCase
{
    private JsonSerializer $serializer;

    protected function setUp(): void
    {
        $this->serializer = new JsonSerializer();
    }

    #[Test]
    #[DataProvider('dataProviderSuccessfulSerialize')]
    public function it_serializes(mixed $data, string $serialized): void
    {
        self::assertSame($this->serializer->serialize($data), $serialized);
    }

    #[Test]
    #[DataProvider('dataProviderSuccessfulDeserialize')]
    public function it_deserializes(string $serialized, mixed $data): void
    {
        self::assertSame($this->serializer->deserialize($serialized), $data);
    }

    #[Test]
    #[DataProvider('dataProviderUnsuccessfulSerialize')]
    public function it_throws_on_serializes(mixed $data, string $message): void
    {
        self::expectException(SerializeException::class);
        self::expectExceptionMessage(sprintf('Unable to serialize: %s', $message));

        $this->serializer->serialize($data);
    }

    #[Test]
    #[DataProvider('dataProviderUnsuccessfulDeserialize')]
    public function it_throws_on_deserializes(string $serialized): void
    {
        $this->expectException(DeserializeException::class);

        $this->serializer->deserialize($serialized);
    }

    public static function dataProviderSuccessfulSerialize(): \Generator
    {
        yield 'null' => [null, 'null'];
        yield 'empty array' => [[], '[]'];
        yield 'empty object' => [new \stdClass(), '{}'];
        yield 'object with properties' => [new class() {
            private string $notSerializable = 'asdf';
            protected int $alsoNotSerializable = 123;
            public string $string = 'test';
            public int $int = 456;
            public bool $bool = true;
        }, '{"string":"test","int":456,"bool":true}'];
    }

    public static function dataProviderSuccessfulDeserialize(): \Generator
    {
        yield 'null' => ['null', null];
        yield 'empty array' => ['[]', []];
        yield 'empty object' => ['{}', []];
        yield 'object with properties' => ['{"string":"test","int":456,"bool":true}', [
            'string' => 'test',
            'int' => 456,
            'bool' => true,
        ]];
    }

    public static function dataProviderUnsuccessfulSerialize(): \Generator
    {
        yield 'NaN' => [NAN, 'Inf and NaN cannot be JSON encoded'];
        yield 'resource' => [tmpfile(), 'Type is not supported'];
    }

    public static function dataProviderUnsuccessfulDeserialize(): \Generator
    {
        yield 'empty string' => [''];
        yield 'single space' => [' '];
        yield 'incorrect json' => ['{'];
        yield 'missing quotes' => ['{"string": test}'];
    }
}
