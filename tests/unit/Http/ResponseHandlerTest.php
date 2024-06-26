<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use PsrMock\Psr7\Response;
use PsrMock\Psr7\Stream;
use Timeular\Http\Exception\AccessDeniedException;
use Timeular\Http\Exception\BadRequestException;
use Timeular\Http\Exception\ConflictException;
use Timeular\Http\Exception\HttpException;
use Timeular\Http\Exception\InternalServerErrorException;
use Timeular\Http\Exception\MissingContentTypeHeaderException;
use Timeular\Http\Exception\MultipleContentTypeValuesException;
use Timeular\Http\Exception\NotFoundException;
use Timeular\Http\Exception\UnauthorizedException;
use Timeular\Http\Exception\UnsupportedMediaTypeException;
use Timeular\Http\Factory\MediaTypeResolverFactory;
use Timeular\Http\Factory\ResponseHandlerFactory;
use Timeular\Http\Factory\SerializerFactory;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\ResponseHandler;
use Timeular\Http\ResponseHandlerInterface;
use Timeular\Http\Serializer\DeserializeException;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\MissingEncoderException;
use Timeular\Http\Serializer\Serializer;

#[CoversClass(ResponseHandler::class)]
#[UsesClass(HttpException::class)]
#[UsesClass(BadRequestException::class)]
#[UsesClass(AccessDeniedException::class)]
#[UsesClass(NotFoundException::class)]
#[UsesClass(ConflictException::class)]
#[UsesClass(UnauthorizedException::class)]
#[UsesClass(UnsupportedMediaTypeException::class)]
#[UsesClass(MissingContentTypeHeaderException::class)]
#[UsesClass(MultipleContentTypeValuesException::class)]
#[UsesClass(MediaTypeResolver::class)]
#[UsesClass(MissingEncoderException::class)]
#[UsesClass(InternalServerErrorException::class)]
#[UsesClass(HttpException::class)]
#[UsesClass(DeserializeException::class)]
#[UsesClass(Serializer::class)]
#[UsesClass(JsonEncoder::class)]
#[UsesClass(MediaTypeResolverFactory::class)]
#[UsesClass(ResponseHandlerFactory::class)]
#[UsesClass(SerializerFactory::class)]
class ResponseHandlerTest extends TestCase
{
    private ResponseHandlerInterface $responseHandler;

    public static function returnTypePerMediaType(): \Generator
    {
        yield '"array" for "application/json"' => ['{}', 'array', 'application/json'];
    }

    public static function exceptionPerStatusCode(): \Generator
    {
        yield '"400" for "BadRequestException"' => [400, BadRequestException::class];
        yield '"403" for "AccessDeniedException"' => [403, AccessDeniedException::class];
        yield '"404" for "NotFoundException"' => [404, NotFoundException::class];
        yield '"409" for "ConflictException"' => [409, ConflictException::class];

        $statusCode = rand(501, 599);
        yield sprintf('"%s" for "HttpException"', $statusCode) => [$statusCode, HttpException::class];
    }

    protected function setUp(): void
    {
        $this->responseHandler = (new ResponseHandlerFactory())->create();
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
    public function it_handles_logout(): void
    {
        $response = (new Response())
            ->withHeader('Set-Cookie', '')
        ;

        $handledData = $this->responseHandler->handle($response);

        self::assertEquals('', $handledData);
    }

    #[Test]
    public function it_handles_incorrect_credentials(): void
    {
        $response = (new Response(401))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream('{"message": "foo"}'))
        ;

        self::expectExceptionObject(UnauthorizedException::withMessage('foo'));

        $this->responseHandler->handle($response);
    }

    #[Test]
    public function it_throws_unauthorized_exception(): void
    {
        $response = (new Response())
            // Intentionally "break" this as that's how it's handled on server side :/
//            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401)
        ;

        self::expectExceptionObject(UnauthorizedException::withMessage());

        $this->responseHandler->handle($response);
    }

    #[Test]
    public function it_throws_internal_server_exception(): void
    {
        $response = (new Response())
            ->withStatus(500)
        ;

        self::expectExceptionObject(InternalServerErrorException::withMessage());

        $this->responseHandler->handle($response);
    }

    #[Test]
    public function it_throws_bad_request_exception_on_missing_header(): void
    {
        $response = (new Response());

        self::expectExceptionObject(BadRequestException::withMessage('Missing "Content-Type" header.'));

        $this->responseHandler->handle($response);
    }

    #[Test]
    public function it_throws_bad_request_exception_on_multiple_header_values(): void
    {
        $response = (new Response())
            ->withHeader('Content-Type', 'application/json')
            ->withAddedHeader('Content-Type', 'text/html')
        ;

        self::expectExceptionObject(BadRequestException::withMessage('Using multiple "Content-Type" headers is not supported.'));

        $this->responseHandler->handle($response);
    }

    #[Test]
    public function it_throws_bad_request_exception_on_deserialization_error(): void
    {
        $response = (new Response())
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(''))
        ;

        self::expectException(BadRequestException::class);

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
