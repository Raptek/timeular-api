<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use PsrMock\Psr7\Request;
use PsrMock\Psr7\Response;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\MediaTypeResolverInterface;

class MediaTypeResolverTest extends TestCase
{
    private MediaTypeResolverInterface $mediaTypeResolver;
    protected function setUp(): void
    {
        $this->mediaTypeResolver = new MediaTypeResolver();
    }

    public static function correctCases(): \Generator
    {
        yield 'Request with "application/json" without charset' => [(new Request())->withHeader('Content-Type', 'application/json'), 'application/json'];
        yield 'Request with "text/html" with single parameter' => [(new Request())->withHeader('Content-Type', 'text/html; charset=utf-8'), 'text/html'];
        yield 'Request with "multipart/form-data" with multiple parameters' => [(new Request())->withHeader('Content-Type', 'multipart/form-data; charset=utf-8; boundary=something'), 'multipart/form-data'];
        yield 'Response with "application/json" without charset' => [(new Response())->withHeader('Content-Type', 'application/json'), 'application/json'];
        yield 'Response with "text/html" with single parameter' => [(new Response())->withHeader('Content-Type', 'text/html; charset=utf-8'), 'text/html'];
        yield 'Response with "multipart/form-data" with multiple parameters' => [(new Response())->withHeader('Content-Type', 'multipart/form-data; charset=utf-8; boundary=something'), 'multipart/form-data'];
    }

    #[Test]
    #[DataProvider('correctCases')]
    public function it_returns_correct_media_type(MessageInterface $message, string $mediaType): void
    {
        self::assertSame($this->mediaTypeResolver->getMediaTypeFromMessage($message), $mediaType);
    }
}
