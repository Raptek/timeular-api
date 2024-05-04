<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Http\Exception\UnsupportedMediaTypeException;

#[CoversClass(UnsupportedMediaTypeException::class)]
class UnsupportedMediaTypeExceptionTest extends TestCase
{
    #[Test]
    public function it_is_created_correctly(): void
    {
        $exception = UnsupportedMediaTypeException::fromMediaType('application/json');

        self::assertInstanceOf(UnsupportedMediaTypeException::class, $exception);
        self::assertSame($exception->getMessage(), 'Media Type "application/json" is not supported.');
        self::assertSame($exception->getCode(), 415);
    }
}
