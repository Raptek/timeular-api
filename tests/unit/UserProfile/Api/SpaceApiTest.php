<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\UserProfile\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use PsrMock\Psr18\Client;
use PsrMock\Psr18\Contracts\ClientContract;
use PsrMock\Psr7\Response;
use PsrMock\Psr7\Stream;
use Timeular\Http\Builder\HttpClientBuilder;
use Timeular\Http\Builder\RequestFactoryBuilder;
use Timeular\Http\Builder\Serializer\SerializerBuilder;
use Timeular\Http\HttpClient;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\ResponseHandler;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\Serializer;
use Timeular\UserProfile\Api\SpaceApi;
use Timeular\UserProfile\Model\RetiredUser;
use Timeular\UserProfile\Model\Space;
use Timeular\UserProfile\Model\User;

#[CoversClass(SpaceApi::class)]
#[UsesClass(HttpClient::class)]
#[UsesClass(MediaTypeResolver::class)]
#[UsesClass(RequestFactory::class)]
#[UsesClass(ResponseHandler::class)]
#[UsesClass(JsonEncoder::class)]
#[UsesClass(Serializer::class)]
#[UsesClass(HttpClientBuilder::class)]
#[UsesClass(RequestFactoryBuilder::class)]
#[UsesClass(SerializerBuilder::class)]
#[UsesClass(RetiredUser::class)]
#[UsesClass(Space::class)]
#[UsesClass(User::class)]
class SpaceApiTest extends TestCase
{
    private SpaceApi $api;
    private ClientContract $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->api = new SpaceApi((new HttpClientBuilder())->withPsrClient($this->client)->build());
    }

    #[Test]
    public function it_returns_spaces(): void
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
    "data": [
        {
            "id": "1",
            "name": "My Personal Space",
            "default": true,
            "members": [
                {
                    "id": "1",
                    "name": "my name",
                    "email": "me-email@example.com",
                    "role": "Admin"
                }
            ],
            "retiredMembers": []
        },
        {
            "id": "2",
            "name": "My Shared Space",
            "default": false,
            "members": [
                {
                    "id": "2",
                    "name": "my friends name",
                    "email": "my-friend@example.com",
                    "role": "Admin"
                },
                {
                    "id": "1",
                    "name": "my name",
                    "email": "me-email@example.com",
                    "role": "Member"
                }
                
            ],
            "retiredMembers": []
        },
        {
            "id": "3",
            "name": "My Shared Space",
            "default": false,
            "members": [
                {
                    "id": "1",
                    "name": "my name",
                    "email": "me-email@example.com",
                    "role": "Admin"
                },
                {
                    "id": "2",
                    "name": "my friends name",
                    "email": "my-friend@example.com",
                    "role": "Member"
                }
                
            ],
            "retiredMembers": [
                {
                    "id": "3",
                    "name": "Former Member 1"
                }
            ]
        }
    ]
}
BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/space', $response);

        $spaces = $this->api->spacesWithMembers();

        self::assertIsArray($spaces);
        self::assertContainsOnlyInstancesOf(Space::class, $spaces);
    }
}
