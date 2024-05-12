<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface as PsrStreamFactoryInterface;
use Timeular\Http\Serializer\SerializerInterface;

readonly class RequestFactory implements RequestFactoryInterface
{
    public function __construct(
        private PsrRequestFactoryInterface $requestFactory,
        private PsrStreamFactoryInterface $streamFactory,
        private SerializerInterface $serializer,
    ) {}

    public function create(string $method, string $uri, array $payload = []): RequestInterface
    {
        $body = $this->streamFactory->createStream($this->serializer->serialize($payload, 'application/json'));

        return $this->requestFactory
            ->createRequest(strtoupper($method), self::BASE_URI . '/' . $uri)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body)
        ;
    }
}
