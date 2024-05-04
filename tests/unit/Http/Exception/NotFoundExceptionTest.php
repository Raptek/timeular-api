<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Http\Exception\NotFoundException;

#[CoversClass(NotFoundException::class)]
class NotFoundExceptionTest extends TestCase
{
    #[Test]
    public function it_is_created_correctly(): void
    {
        $exception = NotFoundException::withMessage();

        self::assertInstanceOf(NotFoundException::class, $exception);
        self::assertSame($exception->getMessage(), 'Not found.');
        self::assertSame($exception->getCode(), 404);

        $exception = NotFoundException::withMessage('Custom message.');

        self::assertInstanceOf(NotFoundException::class, $exception);
        self::assertSame($exception->getMessage(), 'Custom message.');
        self::assertSame($exception->getCode(), 404);
    }
}
