<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Http\Exception\ConflictException;

#[CoversClass(ConflictException::class)]
class ConflictExceptionTest extends TestCase
{
    #[Test]
    public function it_is_created_correctly(): void
    {
        $exception = ConflictException::withMessage();

        self::assertInstanceOf(ConflictException::class, $exception);
        self::assertSame($exception->getMessage(), 'Conflict.');
        self::assertSame($exception->getCode(), 409);

        $exception = ConflictException::withMessage('Custom message.');

        self::assertInstanceOf(ConflictException::class, $exception);
        self::assertSame($exception->getMessage(), 'Custom message.');
        self::assertSame($exception->getCode(), 409);
    }
}
