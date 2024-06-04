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
use Timeular\TimeTracking\Api\TimeEntriesApi;
use Timeular\TimeTracking\Model\Duration;
use Timeular\TimeTracking\Model\Mention;
use Timeular\TimeTracking\Model\Note;
use Timeular\TimeTracking\Model\Tag;
use Timeular\TimeTracking\Model\TimeEntry;

#[CoversClass(TimeEntriesApi::class)]
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
#[UsesClass(Duration::class)]
#[UsesClass(Mention::class)]
#[UsesClass(Note::class)]
#[UsesClass(Tag::class)]
#[UsesClass(TimeEntry::class)]
#[UsesClass(AuthApi::class)]
class TimeEntriesApiTest extends TestCase
{
    private TimeEntriesApi $api;
    private ClientContract $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->api = new TimeEntriesApi((new HttpClientFactory($this->client))->create());
    }

    #[Test]
    public function it_finds_time_entry(): void
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
  "timeEntries": [
    {
      "id": "1",
      "activityId": "1",
      "duration": {
        "startedAt": "2020-01-01T00:00:00.000",
        "stoppedAt": "2020-01-01T01:00:00.000"
      },
      "note": {
        "text": "If there is a possibility of several things going wrong, the one that will cause the most damage will be the one to go wrong <{{|t|2|}}> <{{|t|1|}}> <{{|m|1|}}> <{{|m|2|}}>",
        "tags": [
          {
            "id": 1,
            "key": "43d10bff-65c6-4aab-879e-8e4b9106c99d",
            "label": "burst",
            "scope": "timeular",
            "spaceId": "1"
          },
          {
            "id": 2,
            "key": "849ab930-4492-4b73-b378-8bff6d12fdd0",
            "label": "interpret",
            "scope": "timeular",
            "spaceId": "1"
          }
        ],
        "mentions": [
          {
            "id": 1,
            "key": "8ed8ca39-813b-4f9d-a1ba-9a18b6c001e0",
            "label": "Joanie",
            "scope": "timeular",
            "spaceId": "1"
          },
          {
            "id": 2,
            "key": "999f14a2-c0d2-430a-b1c0-23495f96b1cb",
            "label": "Violette",
            "scope": "timeular",
            "spaceId": "1"
          }
        ]
      }
    }
  ]
}
BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/time-entries/2016-01-01T00:00:00.000/2017-12-31T23:59:59.999', $response);

        $data = $this->api->find(new \DateTimeImmutable('2016-01-01T00:00:00.000'), new \DateTimeImmutable('2017-12-31T23:59:59.999'));

        self::assertIsArray($data);
        self::assertContainsOnlyInstancesOf(TimeEntry::class, $data);
    }

    #[Test]
    public function it_creates_time_entry(): void
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
  "id": "1",
  "activityId": "1",
  "duration": {
    "startedAt": "2016-08-05T06:00:00.000",
    "stoppedAt": "2016-08-05T07:00:00.000"
  },
  "note": {
    "text": "99 sheep <{{|t|1|}}> <{{|m|1|}}>",
    "tags": [
      {
        "id": 1,
        "key": "dabf59bc-9997-44ca-9451-e753a11090c4",
        "label": "some-tag",
        "scope": "timeular",
        "spaceId": "1"
      }
    ],
    "mentions": [
      {
        "id": 1,
        "key": "debf59bc-9997-44ca-9451-e753a11090c4",
        "label": "some mention",
        "scope": "timeular",
        "spaceId": "1"
      }
    ]
  },
  "errors": []
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/time-entries', $response);

        $timeEntry = $this->api->create(
            '1',
            new \DateTimeImmutable('2016-01-01T00:00:00.000'),
            new \DateTimeImmutable('2017-12-31T23:59:59.999'),
            '99 sheep <{{|t|1|}}> <{{|m|1|}}>',
        );

        self::assertInstanceOf(TimeEntry::class, $timeEntry);
    }

    #[Test]
    public function it_finds_time_entry_by_id(): void
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
  "id": "3",
  "activityId": "1",
  "duration": {
    "startedAt": "2016-08-05T06:00:00.000",
    "stoppedAt": "2016-08-05T07:00:00.000"
  },
  "note": {
    "text": "99 sheep <{{|t|1|}}> <{{|m|1|}}>",
    "tags": [
      {
        "id": 1,
        "key": "dabf59bc-9997-44ca-9451-e753a11090c4",
        "label": "some-tag",
        "scope": "timeular",
        "spaceId": "1"
      }
    ],
    "mentions": [
      {
        "id": 1,
        "key": "debf59bc-9997-44ca-9451-e753a11090c4",
        "label": "some mention",
        "scope": "timeular",
        "spaceId": "1"
      }
    ]
  },
  "errors": []
}
BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/time-entries/3', $response);

        $timeEntry = $this->api->findById('3');

        self::assertInstanceOf(TimeEntry::class, $timeEntry);
    }

    #[Test]
    public function it_edits_time_entry(): void
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
  "id": "1",
  "activityId": "1",
  "duration": {
    "startedAt": "2016-08-05T06:00:00.000",
    "stoppedAt": "2016-08-05T07:00:00.000"
  },
  "note": {
    "text": "200 sheep <{{|t|1|}}> <{{|m|1|}}>",
    "tags": [
      {
        "id": 1,
        "key": "dabf59bc-9997-44ca-9451-e753a11090c4",
        "label": "some-tag",
        "scope": "timeular",
        "spaceId": "1"
      }
    ],
    "mentions": [
      {
        "id": 1,
        "key": "debf59bc-9997-44ca-9451-e753a11090c4",
        "label": "some mention",
        "scope": "timeular",
        "spaceId": "1"
      }
    ]
  },
  "errors": []
}
BODY,
            ))
        ;
        $this->client->addResponse('PATCH', RequestFactoryInterface::BASE_URI . '/time-entries/3', $response);

        $timeEntry = $this->api->edit(
            '3',
            '1',
            new \DateTimeImmutable('2016-08-05T06:00:00.000'),
            new \DateTimeImmutable('2016-08-05T07:00:00.000'),
            '200 sheep <{{|t|1|}}> <{{|m|1|}}>',
        );

        self::assertInstanceOf(TimeEntry::class, $timeEntry);
    }
}
