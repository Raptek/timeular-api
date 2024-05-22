<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http\Serializer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Timeular\Http\Builder\Serializer\SerializerBuilder;
use Timeular\Http\Serializer\DeserializeException;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\MissingEncoderException;
use Timeular\Http\Serializer\PassthroughEncoder;
use Timeular\Http\Serializer\SerializeException;
use Timeular\Http\Serializer\Serializer;
use Timeular\Http\Serializer\SerializerInterface;

#[CoversClass(Serializer::class)]
#[UsesClass(JsonEncoder::class)]
#[UsesClass(PassthroughEncoder::class)]
#[UsesClass(SerializeException::class)]
#[UsesClass(DeserializeException::class)]
#[UsesClass(MissingEncoderException::class)]
#[UsesClass(SerializerBuilder::class)]
class SerializerTest extends TestCase
{
    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        $this->serializer = (new SerializerBuilder())->build();
    }

    #[Test]
    #[DataProvider('dataProviderSuccessfulSerialize')]
    public function it_serializes(mixed $data, string $serialized, string $format): void
    {
        self::assertSame($this->serializer->serialize($data, $format), $serialized);
    }

    #[Test]
    #[DataProvider('dataProviderSuccessfulDeserialize')]
    public function it_deserializes(string $serialized, mixed $data, string $format): void
    {
        self::assertSame($this->serializer->deserialize($serialized, $format), $data);
    }

    #[Test]
    #[DataProvider('dataProviderUnsuccessfulSerialize')]
    public function it_throws_on_serializes(mixed $data, string $message, string $format): void
    {
        self::expectException(SerializeException::class);
        self::expectExceptionMessage(sprintf('Unable to serialize: %s', $message));

        $this->serializer->serialize($data, $format);
    }

    #[Test]
    #[DataProvider('dataProviderUnsuccessfulDeserialize')]
    public function it_throws_on_deserializes(string $serialized, string $message, string $format): void
    {
        $this->expectException(DeserializeException::class);
        self::expectExceptionMessage(sprintf('Unable to deserialize: %s', $message));

        $this->serializer->deserialize($serialized, $format);
    }

    #[Test]
    #[DataProvider('dataProviderUnsupportedFormat')]
    public function it_throws_on_unsupported_format_during_serialization(string $format): void
    {
        $this->expectException(MissingEncoderException::class);
        self::expectExceptionMessage(sprintf('Encoder for format "%s" does not exist', $format));

        $this->serializer->serialize('test', $format);
    }

    #[Test]
    #[DataProvider('dataProviderUnsupportedFormat')]
    public function it_throws_on_unsupported_format_during_deserialization(string $format): void
    {
        $this->expectException(MissingEncoderException::class);
        self::expectExceptionMessage(sprintf('Encoder for format "%s" does not exist', $format));

        $this->serializer->deserialize('test', $format);
    }

    public static function dataProviderSuccessfulSerialize(): \Generator
    {
        yield 'null' => [null, 'null', 'application/json'];
        yield 'empty array' => [[], '[]', 'application/json'];
        yield 'empty object' => [new \stdClass(), '{}', 'application/json'];
        yield 'object with properties' => [new class () {
            private string $notSerializable = 'asdf';
            protected int $alsoNotSerializable = 123;
            public string $string = 'test';
            public int $int = 456;
            public bool $bool = true;
        }, '{"string":"test","int":456,"bool":true}', 'application/json'];
    }

    public static function dataProviderSuccessfulDeserialize(): \Generator
    {
        yield 'null' => ['null', [], 'application/json'];
        yield 'empty array' => ['[]', [], 'application/json'];
        yield 'empty object' => ['{}', [], 'application/json'];
        yield 'object with properties' => ['{"string":"test","int":456,"bool":true}', [
            'string' => 'test',
            'int' => 456,
            'bool' => true,
        ], 'application/json'];
        yield 'csv as string' => ['test', 'test', 'text/csv'];
        yield 'xlsx as string' => ['test', 'test', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    }

    public static function dataProviderUnsuccessfulSerialize(): \Generator
    {
        yield 'NaN' => [NAN, 'Inf and NaN cannot be JSON encoded', 'application/json'];
        yield 'resource' => [tmpfile(), 'Type is not supported', 'application/json'];
    }

    public static function dataProviderUnsuccessfulDeserialize(): \Generator
    {
        yield 'empty string' => ['', 'Syntax error', 'application/json'];
        yield 'single space' => [' ', 'Syntax error', 'application/json'];
        yield 'incorrect json' => ['{', 'Syntax error', 'application/json'];
        yield 'missing quotes' => ['{"string": test}', 'Syntax error', 'application/json'];
    }

    public static function dataProviderUnsupportedFormat(): \Generator
    {
        yield 'text/plain' => ['text/plain'];
        yield 'text/html' => ['text/html'];
    }
}
