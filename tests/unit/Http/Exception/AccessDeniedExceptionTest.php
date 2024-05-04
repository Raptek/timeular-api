<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Http\Exception\AccessDeniedException;

#[CoversClass(AccessDeniedException::class)]
class AccessDeniedExceptionTest extends TestCase
{
    #[Test]
    public function it_is_created_correctly(): void
    {
        $exception = AccessDeniedException::withMessage();

        self::assertInstanceOf(AccessDeniedException::class, $exception);
        self::assertSame($exception->getMessage(), 'Access denied.');
        self::assertSame($exception->getCode(), 403);

        $exception = AccessDeniedException::withMessage('Custom message.');

        self::assertInstanceOf(AccessDeniedException::class, $exception);
        self::assertSame($exception->getMessage(), 'Custom message.');
        self::assertSame($exception->getCode(), 403);
    }
}
