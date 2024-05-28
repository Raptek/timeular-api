<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Timeular\RequestFactoryFactory;
use Timeular\Http\Factory\SerializerFactory;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\Serializer;
use Timeular\Http\Serializer\SerializerInterface;

#[CoversClass(RequestFactory::class)]
#[UsesClass(MediaTypeResolver::class)]
#[UsesClass(JsonEncoder::class)]
#[UsesClass(Serializer::class)]
#[UsesClass(SerializerFactory::class)]
class RequestFactoryTest extends TestCase
{
    private RequestFactoryInterface $requestFactory;
    private SerializerInterface $serializer;

    public static function prepareRequest(): \Generator
    {
        $uri = bin2hex(random_bytes(random_int(8, 32)));
        yield sprintf('"%s" to "%s"', 'GET', $uri) => ['GET', $uri, []];

        foreach (['POST', 'PUT', 'PATCH', 'DELETE'] as $method) {
            $uri = bin2hex(random_bytes(random_int(8, 32)));

            yield sprintf('"%s" to "%s" with empty payload', $method, $uri) => [$method, $uri, []];
            yield sprintf('"%s" to "%s" with payload', $method, $uri) => [$method, $uri, ['test' => 123]];
        }
    }

    #[Test]
    #[DataProvider('prepareRequest')]
    public function it_correctly_creates_request(string $method, string $uri, array $payload): void
    {
        $request = $this->requestFactory->create($method, $uri, $payload);

        self::assertEquals($method, $request->getMethod());
        self::assertEquals(sprintf('%s/%s', RequestFactory::BASE_URI, $uri), (string) $request->getUri());
        self::assertEquals('application/json', $request->getHeaderLine('Content-Type'));
        self::assertEquals($this->serializer->serialize($payload, 'application/json'), $request->getBody()->getContents());
    }

    protected function setUp(): void
    {
        $this->requestFactory = (new RequestFactoryFactory())->create();
        $this->serializer = (new SerializerFactory())->create();
    }
}
