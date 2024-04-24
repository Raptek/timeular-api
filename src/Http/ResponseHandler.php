<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Message\ResponseInterface;
use Timeular\Http\Exception\AccessDeniedException;
use Timeular\Http\Exception\BadRequestException;
use Timeular\Http\Exception\HttpException;
use Timeular\Http\Exception\MissingContentTypeHeaderException;
use Timeular\Http\Exception\MultipleContentTypeValuesException;
use Timeular\Http\Exception\NotFoundException;
use Timeular\Http\Exception\UnauthorizedException;
use Timeular\Http\Exception\UnsupportedMediaTypeException;
use Timeular\Http\Serializer\MissingEncoderException;
use Timeular\Http\Serializer\SerializerInterface;

readonly class ResponseHandler
{
    public function __construct(
        private SerializerInterface $serializer,
        private MediaTypeResolverInterface $mediaTypeResolver,
    ) {
    }

    public function handle(ResponseInterface $response): string|array
    {
        $statusCode = $response->getStatusCode();

        if (401 === $statusCode) {
            throw UnauthorizedException::create();
        }

        try {
            $mediaType = $this->mediaTypeResolver->getMediaTypeFromMessage($response);
        } catch (MissingContentTypeHeaderException | MultipleContentTypeValuesException $exception) {
            throw BadRequestException::withMessage($exception->getMessage());
        }

        $body = $response->getBody()->getContents();

        try {
            $data = $this->serializer->deserialize($body, $mediaType);
        } catch (MissingEncoderException) {
            throw UnsupportedMediaTypeException::fromMediaType($mediaType);
        }

        if (200 !== $statusCode) {
            throw match ($statusCode) {
                400 => BadRequestException::withMessage($data['message']),
                403 => AccessDeniedException::withMessage($data['message']),
                404 => NotFoundException::withMessage($response['message']),
                default => new HttpException($data['message'], $statusCode),
            };
        }

        return $data;
    }
}
