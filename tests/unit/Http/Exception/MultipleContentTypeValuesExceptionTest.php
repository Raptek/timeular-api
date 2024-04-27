<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http\Exception;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Http\Exception\MultipleContentTypeValuesException;

class MultipleContentTypeValuesExceptionTest extends TestCase
{
    #[Test]
    public function it_is_created_correctly(): void
    {
        $exception = MultipleContentTypeValuesException::create();

        self::assertInstanceOf(MultipleContentTypeValuesException::class, $exception);
        self::assertSame($exception->getMessage(), 'Using multiple "Content-Type" headers is not supported.');
    }
}
