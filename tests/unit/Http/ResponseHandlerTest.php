<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PsrMock\Psr7\Response;
use PsrMock\Psr7\Stream;
use Timeular\Http\Exception\AccessDeniedException;
use Timeular\Http\Exception\BadRequestException;
use Timeular\Http\Exception\HttpException;
use Timeular\Http\Exception\NotFoundException;
use Timeular\Http\Exception\UnauthorizedException;
use Timeular\Http\Exception\UnsupportedMediaTypeException;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\ResponseHandler;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\PassthroughEncoder;
use Timeular\Http\Serializer\Serializer;

class ResponseHandlerTest extends TestCase
{
    private ResponseHandler $responseHandler;

    public static function returnTypePerMediaType(): \Generator
    {
        yield '"array" for "application/json"' => ['{}', 'array', 'application/json'];
    }

    public static function exceptionPerStatusCode(): \Generator
    {
        yield '"400" for "BadRequestException"' => [400, BadRequestException::class];
        yield '"403" for "AccessDeniedException"' => [403, AccessDeniedException::class];
        yield '"404" for "NotFoundException"' => [404, NotFoundException::class];

        $statusCode = rand(500, 599);
        yield sprintf('"%s" for "HttpException"', $statusCode) => [$statusCode, HttpException::class];
    }

    protected function setUp(): void
    {
        $this->responseHandler = new ResponseHandler(
            new Serializer(
                [
                    'application/json' => new JsonEncoder(),
                    'text/csv' => new PassthroughEncoder(),
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => new PassthroughEncoder(),
                ]
            ),
            new MediaTypeResolver(),
        );
    }

    #[Test]
    #[DataProvider('returnTypePerMediaType')]
    public function it_returns_correct_data_for_media_type(string $data, string $format, string $mediaType): void
    {
        $body = new Stream($data);

        $response = (new Response())
            ->withHeader('Content-Type', $mediaType)
            ->withBody($body)
        ;

        $handledData = $this->responseHandler->handle($response);

        self::assertEquals($format, gettype($handledData));
    }

    #[Test]
    public function it_throws_unauthorized_exception(): void
    {
        $response = (new Response())
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401)
        ;

        self::expectExceptionObject(UnauthorizedException::create());

        $this->responseHandler->handle($response);
    }

    #[Test]
    public function it_throws_exception_on_unsupported_media(): void
    {
        $response = (new Response())
            ->withHeader('Content-Type', 'text/html')
        ;

        self::expectExceptionObject(UnsupportedMediaTypeException::fromMediaType('text/html'));

        $this->responseHandler->handle($response);
    }

    #[Test]
    #[DataProvider('exceptionPerStatusCode')]
    public function it_throws_correct_exception(int $statusCode, string $exceptionClass): void
    {
        $body = new Stream('{"message": "foo"}');
        $response = (new Response())
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body)
            ->withStatus($statusCode)
        ;

        self::expectException($exceptionClass);

        $this->responseHandler->handle($response);
    }
}
