<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Auth\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use PsrMock\Psr18\Client;
use PsrMock\Psr18\Contracts\ClientContract;
use PsrMock\Psr7\Response;
use PsrMock\Psr7\Stream;
use Tests\Unit\Timeular\HttpClientFactory;
use Timeular\Auth\Api\AuthApi;
use Timeular\Http\Factory\MediaTypeResolverFactory;
use Timeular\Http\Factory\RequestFactoryFactory;
use Timeular\Http\Factory\ResponseHandlerFactory;
use Timeular\Http\Factory\SerializerFactory;
use Timeular\Http\HttpClient;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\ResponseHandler;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\Serializer;

#[CoversClass(AuthApi::class)]
#[UsesClass(MediaTypeResolverFactory::class)]
#[UsesClass(RequestFactoryFactory::class)]
#[UsesClass(ResponseHandlerFactory::class)]
#[UsesClass(SerializerFactory::class)]
#[UsesClass(HttpClient::class)]
#[UsesClass(MediaTypeResolver::class)]
#[UsesClass(RequestFactory::class)]
#[UsesClass(ResponseHandler::class)]
#[UsesClass(JsonEncoder::class)]
#[UsesClass(Serializer::class)]
class AuthApiTest extends TestCase
{
    private AuthApi $api;
    private ClientContract $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->api = new AuthApi(
            (new HttpClientFactory($this->client))->create(),
        );
    }

    #[Test]
    public function it_signs_in(): void
    {
        $response = (new Response(200))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(
                <<<BODY
{
  "token": "1234abcdEFGH"
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/developer/sign-in', $response);

        $token = $this->api->signIn('test', 'test');

        self::assertIsString($token);
    }

    #[Test]
    public function it_fetches_api_key(): void
    {
        $authorizationResponse = (new Response(200))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(json_encode(['token' => 'token'])))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/developer/sign-in', $authorizationResponse);

        $response = (new Response(200))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(
                <<<BODY
{
  "apiKey": "ABCDefgh1234="
}
BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/developer/api-access', $response);

        $apiKey = $this->api->fetchApiKey();

        self::assertIsString($apiKey);
    }

    #[Test]
    public function it_regenerates_key_pair(): void
    {
        $authorizationResponse = (new Response(200))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(json_encode(['token' => 'token'])))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/developer/sign-in', $authorizationResponse);

        $response = (new Response(200))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(
                <<<BODY
{
  "apiKey": "ABCDefgh1234=",
  "apiSecret": "EFGHijkl5678="
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/developer/api-access', $response);

        $keyPair = $this->api->regenerateKeyPair();

        self::assertIsArray($keyPair);
    }
}
