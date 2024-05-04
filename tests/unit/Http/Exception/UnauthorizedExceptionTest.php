<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Http\Exception\UnauthorizedException;

#[CoversClass(UnauthorizedException::class)]
class UnauthorizedExceptionTest extends TestCase
{
    #[Test]
    public function it_is_created_correctly(): void
    {
        $exception = UnauthorizedException::withMessage();

        self::assertInstanceOf(UnauthorizedException::class, $exception);
        self::assertSame($exception->getMessage(), 'Unauthorized.');
        self::assertSame($exception->getCode(), 401);

        $exception = UnauthorizedException::withMessage('Custom message.');

        self::assertInstanceOf(UnauthorizedException::class, $exception);
        self::assertSame($exception->getMessage(), 'Custom message.');
        self::assertSame($exception->getCode(), 401);
    }
}
