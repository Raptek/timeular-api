<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use PsrMock\Psr18\Client;
use PsrMock\Psr7\Response;
use PsrMock\Psr7\Stream;
use Tests\Builders\Timeular\Http\HttpClientBuilder;
use Tests\Builders\Timeular\Http\RequestFactoryBuilder;
use Timeular\Http\HttpClient;
use Timeular\Http\HttpClientInterface;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\ResponseHandler;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\Serializer;

#[CoversClass(HttpClient::class)]
#[UsesClass(MediaTypeResolver::class)]
#[UsesClass(RequestFactory::class)]
#[UsesClass(ResponseHandler::class)]
#[UsesClass(JsonEncoder::class)]
#[UsesClass(Serializer::class)]
class HttpClientTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private PsrClientInterface $psrClient;
    private RequestFactoryInterface $requestFactory;

    protected function setUp(): void
    {
        $this->psrClient = new Client();
        $this->requestFactory = (new RequestFactoryBuilder())->build();
        $this->httpClient = (new HttpClientBuilder())
            ->withApiKey('test')
            ->withApiSecret('test')
            ->withPsrClient($this->psrClient)
            ->withRequestFactory($this->requestFactory)
            ->build()
        ;
    }

    #[Test]
    public function it_handles_authorization(): void
    {
        $authorizationResponse = (new Response(200))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(json_encode([])))
            ->withBody(new Stream(json_encode(['token' => 'token'])))
        ;
        $this->psrClient->addResponse('POST', RequestFactoryInterface::BASE_URI . '/developer/sign-in', $authorizationResponse);

        $response = (new Response(200))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(json_encode([])))
        ;
        $this->psrClient->addResponse('GET', RequestFactoryInterface::BASE_URI . '/test/endpoint', $response);

        $data = $this->httpClient->request('GET', 'test/endpoint');

        self::assertIsArray($data);
    }
}
