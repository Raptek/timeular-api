<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Message\ResponseInterface;
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
use Timeular\Http\Serializer\DeserializeException;
use Timeular\Http\Serializer\MissingEncoderException;
use Timeular\Http\Serializer\SerializerInterface;

readonly class ResponseHandler implements ResponseHandlerInterface
{
    public function __construct(
        private MediaTypeResolverInterface $mediaTypeResolver,
        private SerializerInterface $serializer,
    ) {}

    public function handle(ResponseInterface $response): string|array
    {
        $statusCode = $response->getStatusCode();

        if (500 === $statusCode) {
            // At this moment, this exception doesn't have Content-Type header, so media type can't be resolved. Also, plain text is returned instead of json
            throw InternalServerErrorException::withMessage();
        }

        if (
            401 === $statusCode
            // Providing incorrect Bearer token results in 401 without Content-Type and empty string as body
            && false === $response->hasHeader('Content-Type')
        ) {
            throw UnauthorizedException::withMessage();
        }

        if (
            200 === $statusCode
            && false === $response->hasHeader('Content-Type')
            && true === $response->hasHeader('Set-Cookie')
        ) {
            // https://api.timeular.com/api/v3/developer/logout returns 200 without Content-Type and empty string as body
            // As we don't need any data from it, we can return anything
            // Probably it could be a 204
            return '';
        } else {
            try {
                $mediaType = $this->mediaTypeResolver->getMediaTypeFromMessage($response);
            } catch (MissingContentTypeHeaderException | MultipleContentTypeValuesException | \Throwable $exception) {
                throw BadRequestException::withMessage($exception->getMessage());
            }
        }

        $body = $response->getBody()->getContents();

        try {
            $data = $this->serializer->deserialize($body, $mediaType);
        } catch (MissingEncoderException) {
            throw UnsupportedMediaTypeException::fromMediaType($mediaType);
        } catch (DeserializeException $exception) {
            throw BadRequestException::withMessage($exception->getMessage());
        }

        if (200 !== $statusCode) {
            throw match ($statusCode) {
                400 => BadRequestException::withMessage($data['message']),
                401 => UnauthorizedException::withMessage($data['message']), // Providing incorrect key/secret results in 401 with proper message, but probably should return 400
                403 => AccessDeniedException::withMessage($data['message']),
                404 => NotFoundException::withMessage($data['message']),
                409 => ConflictException::withMessage($data['message']),
                default => HttpException::create($data['message'], $statusCode),
            };
        }

        return $data;
    }
}
