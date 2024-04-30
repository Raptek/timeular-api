<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use PsrMock\Psr17\RequestFactory as Psr17RequestFactory;

class RequestFactoryTest extends TestCase
{
    private RequestFactoryInterface $requestFactory;

    public static function prepareRequest(): \Generator
    {
        foreach (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $method) {
            $uri = bin2hex(random_bytes(random_int(8, 32)));

            yield sprintf('"%s" to "%s"', $method, $uri) => [$method, $uri, ['test' => 123]];
        }
    }

    protected function setUp(): void
    {
        $this->requestFactory = new RequestFactory(
            new Psr17RequestFactory(),
            new MediaTypeResolver(),
        );
    }

    #[Test]
    #[DataProvider('prepareRequest')]
    public function it_correctly_creates_request(string $method, string $uri, array $payload): void
    {
        $request = $this->requestFactory->create($method, $uri, $payload);

        self::assertEquals($method, $request->getMethod());
        self::assertEquals(sprintf('%s/%s', RequestFactory::BASE_URI, $uri), (string) $request->getUri());
        self::assertEquals('application/json', $request->getHeaderLine('Content-Type'));
        // @todo Find workaround, as this is not working :/
//        self::assertEquals($this->serializer->serialize($payload, 'application/json'), $request->getBody()->getContents());
    }
}
