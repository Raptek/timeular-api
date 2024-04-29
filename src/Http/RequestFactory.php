<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Timeular\Http\Serializer\SerializerInterface;

readonly class RequestFactory implements RequestFactoryInterface
{
    public const string BASE_URI = 'https://api.timeular.com/api/v3';

    public function __construct(
        private PsrRequestFactoryInterface $requestFactory,
        private SerializerInterface $serializer,
        private MediaTypeResolverInterface $mediaTypeResolver,
    ) {
    }

    public function create(string $method, string $uri, array $payload = []): RequestInterface
    {
        $request = $this->requestFactory
            ->createRequest(strtoupper($method), self::BASE_URI . '/'.  $uri)
            ->withHeader('Content-Type', 'application/json')
        ;

        if ([] !== $payload) {
            $request->getBody()->write($this->serializer->serialize($payload, $this->mediaTypeResolver->getMediaTypeFromMessage($request)));
        }

        return $request;
    }
}
