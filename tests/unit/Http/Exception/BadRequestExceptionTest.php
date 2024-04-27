<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http\Exception;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Http\Exception\BadRequestException;

class BadRequestExceptionTest extends TestCase
{
    #[Test]
    public function it_is_created_correctly(): void
    {
        $exception = BadRequestException::withMessage();

        self::assertInstanceOf(BadRequestException::class, $exception);
        self::assertSame($exception->getMessage(), 'Bad request.');
        self::assertSame($exception->getCode(), 400);

        $exception = BadRequestException::withMessage('Custom message.');

        self::assertInstanceOf(BadRequestException::class, $exception);
        self::assertSame($exception->getMessage(), 'Custom message.');
        self::assertSame($exception->getCode(), 400);
    }
}
