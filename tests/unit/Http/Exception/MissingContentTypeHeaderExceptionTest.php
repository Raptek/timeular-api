<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Http\Exception\MissingContentTypeHeaderException;

#[CoversClass(MissingContentTypeHeaderException::class)]
class MissingContentTypeHeaderExceptionTest extends TestCase
{
    #[Test]
    public function it_is_created_correctly(): void
    {
        $exception = MissingContentTypeHeaderException::create();

        self::assertInstanceOf(MissingContentTypeHeaderException::class, $exception);
        self::assertSame($exception->getMessage(), 'Missing "Content-Type" header.');
    }

}
