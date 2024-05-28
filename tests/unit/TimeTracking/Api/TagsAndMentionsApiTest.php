<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\TimeTracking\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use PsrMock\Psr18\Client;
use PsrMock\Psr18\Contracts\ClientContract;
use PsrMock\Psr7\Response;
use PsrMock\Psr7\Stream;
use Tests\Unit\Timeular\HttpClientFactory;
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
use Timeular\TimeTracking\Api\TagsAndMentionsApi;
use Timeular\TimeTracking\Model\Mention;
use Timeular\TimeTracking\Model\Tag;

#[CoversClass(TagsAndMentionsApi::class)]
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
#[UsesClass(Tag::class)]
#[UsesClass(Mention::class)]
class TagsAndMentionsApiTest extends TestCase
{
    private TagsAndMentionsApi $api;
    private ClientContract $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->api = new TagsAndMentionsApi((new HttpClientFactory($this->client))->create());
    }

    #[Test]
    public function it_returns_list_of_tags_and_mentions(): void
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
    "tags": [
        {
            "id": 1,
            "key": "1234",
            "label": "some-tag",
            "scope": "timeular",
            "spaceId": "1"
        }
    ],
    "mentions": [
        {
            "id": 1,
            "key": "4321",
            "label": "some mention",
            "scope": "timeular",
            "spaceId": "1"
        }
    ]
}
BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/tags-and-mentions', $response);

        $data = $this->api->tagsAndMentions();

        self::assertIsArray($data);
        self::assertIsArray($data['tags']);
        self::assertIsArray($data['mentions']);
        self::assertContainsOnlyInstancesOf(Tag::class, $data['tags']);
        self::assertContainsOnlyInstancesOf(Mention::class, $data['mentions']);
    }

    #[Test]
    public function it_creates_tag(): void
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
  "id": 1,
  "key": "tagtagtag",
  "label": "my new tag",
  "scope": "timeular",
  "spaceId": "1"
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/tags', $response);

        $tag = $this->api->createTag('tagtagtag', 'my new tag', 'timeular', '1');

        self::assertInstanceOf(Tag::class, $tag);
    }

    #[Test]
    public function it_updates_tag(): void
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
  "id": 1,
  "key": "44f8feac-da6f-4e08-8a84-0cc5181d32d7",
  "label": "New Label for tag",
  "scope": "timeular",
  "spaceId": "1"
}
BODY,
            ))
        ;
        $this->client->addResponse('PATCH', RequestFactoryInterface::BASE_URI . '/tags/1', $response);

        $tag = $this->api->updateTag('1', 'New Label for tag');

        self::assertInstanceOf(Tag::class, $tag);
    }

    #[Test]
    public function it_deletes_tag(): void
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
  "timeEntryIds": [
    1234
  ],
  "trackingEdited": null
}
BODY,
            ))
        ;
        $this->client->addResponse('DELETE', RequestFactoryInterface::BASE_URI . '/tags/1', $response);

        $data = $this->api->deleteTag('1');

        self::assertIsArray($data);
        self::assertArrayHasKey('timeEntryIds', $data);
        self::assertArrayHasKey('trackingEdited', $data);
    }

    #[Test]
    public function it_creates_mention(): void
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
  "id": 1,
  "key": "mention",
  "label": "my new mention",
  "scope": "timeular",
  "spaceId": "1"
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/mentions', $response);

        $tag = $this->api->createMention('tagtagtag', 'my new mention', 'timeular', '1');

        self::assertInstanceOf(Mention::class, $tag);
    }

    #[Test]
    public function it_updates_mention(): void
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
  "id": 557667,
  "key": "mention",
  "label": "My new mention label",
  "scope": "timeular",
  "spaceId": "6"
}
BODY,
            ))
        ;
        $this->client->addResponse('PATCH', RequestFactoryInterface::BASE_URI . '/mentions/557667', $response);

        $tag = $this->api->updateMention('557667', 'My new mention label');

        self::assertInstanceOf(Mention::class, $tag);
    }

    #[Test]
    public function it_deletes_mention(): void
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
  "timeEntryIds": [
    1234
  ],
  "trackingEdited": null
}
BODY,
            ))
        ;
        $this->client->addResponse('DELETE', RequestFactoryInterface::BASE_URI . '/mentions/1', $response);

        $data = $this->api->deleteMention('1');

        self::assertIsArray($data);
        self::assertArrayHasKey('timeEntryIds', $data);
        self::assertArrayHasKey('trackingEdited', $data);
    }
}
