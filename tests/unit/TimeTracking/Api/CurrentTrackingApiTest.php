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
use Timeular\TimeTracking\Api\CurrentTrackingApi;
use Timeular\TimeTracking\Model\ActiveTimeEntry;
use Timeular\TimeTracking\Model\Duration;
use Timeular\TimeTracking\Model\Mention;
use Timeular\TimeTracking\Model\Note;
use Timeular\TimeTracking\Model\Tag;
use Timeular\TimeTracking\Model\TimeEntry;

#[CoversClass(CurrentTrackingApi::class)]
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
#[UsesClass(ActiveTimeEntry::class)]
#[UsesClass(TimeEntry::class)]
#[UsesClass(Duration::class)]
#[UsesClass(Mention::class)]
#[UsesClass(Note::class)]
#[UsesClass(Tag::class)]
class CurrentTrackingApiTest extends TestCase
{
    private CurrentTrackingApi $api;
    private ClientContract $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->api = new CurrentTrackingApi((new HttpClientFactory($this->client))->create());
    }

    #[Test]
    public function it_shows_currently_tracked_entry(): void
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
  "currentTracking": {
    "id": 1,
    "activityId": "1217348",
    "startedAt": "2020-08-03T04:00:00.000",
    "note": {
      "text": "99 sheep <{{|t|1|}}> <{{|m|2|}}>",
      "tags": [
        {
          "id": 1,
          "key": "3b381b24-c690-4000-9079-7355579162fb",
          "label": "some tag",
          "scope": "timeular",
          "spaceId": "1"
        }
      ],
      "mentions": [
        {
          "id": 2,
          "key": "2b381b24-c690-4000-9079-7355579162fb",
          "label": "some-mention",
          "scope": "timeular",
          "spaceId": "1"
        }
      ]
    }
  }
}
BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/tracking', $response);

        $activeTimeEntry = $this->api->show();

        self::assertInstanceOf(ActiveTimeEntry::class, $activeTimeEntry);
    }

    #[Test]
    public function it_starts_tracking(): void
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
  "currentTracking": {
    "id": 1,
    "activityId": "1217348",
    "startedAt": "2020-08-03T04:00:00.000",
    "note": {
      "text": "99 sheep <{{|t|1|}}> <{{|m|2|}}>",
      "tags": [
        {
          "id": 1,
          "key": "3b381b24-c690-4000-9079-7355579162fb",
          "label": "some tag",
          "scope": "timeular",
          "spaceId": "1"
        }
      ],
      "mentions": [
        {
          "id": 2,
          "key": "2b381b24-c690-4000-9079-7355579162fb",
          "label": "some-mention",
          "scope": "timeular",
          "spaceId": "1"
        }
      ]
    }
  }
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/tracking/1217348/start', $response);

        $activeTimeEntry = $this->api->start('1217348', new \DateTimeImmutable());

        self::assertInstanceOf(ActiveTimeEntry::class, $activeTimeEntry);
    }

    #[Test]
    public function it_edits_currently_tracked_entry(): void
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
  "currentTracking": {
    "id": 1,
    "activityId": "1217348",
    "startedAt": "2020-08-03T04:00:00.000",
    "note": {
      "text": "99 sheep <{{|t|1|}}> <{{|m|2|}}>",
      "tags": [
        {
          "id": 1,
          "key": "3b381b24-c690-4000-9079-7355579162fb",
          "label": "some tag",
          "scope": "timeular",
          "spaceId": "1"
        }
      ],
      "mentions": [
        {
          "id": 2,
          "key": "2b381b24-c690-4000-9079-7355579162fb",
          "label": "some-mention",
          "scope": "timeular",
          "spaceId": "1"
        }
      ]
    }
  }
}
BODY,
            ))
        ;
        $this->client->addResponse('PATCH', RequestFactoryInterface::BASE_URI . '/tracking', $response);

        $activeTimeEntry = $this->api->edit('1217348');

        self::assertInstanceOf(ActiveTimeEntry::class, $activeTimeEntry);
    }

    #[Test]
    public function it_stops_tracking(): void
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
  "createdTimeEntry": {
    "id": "34714420",
    "activityId": "1217348",
    "duration": {
      "startedAt": "2020-02-03T04:00:00.000",
      "stoppedAt": "2020-08-03T05:00:00.000"
    },
    "note": {
      "text": null,
      "tags": [],
      "mentions": []
    }
  },
  "errors": [
    "Third Party Service error: status code = 400, message = 'no integration found for 111525 and integrationType toggl'",
    "Third Party Service error: status code = 400, message = 'no integration found for 111525 and integrationType toggl'",
    "Third Party Service error: status code = 400, message = 'no integration found for 111525 and integrationType harvest'",
    "Third Party Service error: status code = 400, message = 'no integration found for 111525 and integrationType harvest'",
    "Third Party Service error: status code = 400, message = 'no integration found for 111525 and integrationType toggl'"
  ]
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/tracking/stop', $response);

        $timeEntry = $this->api->stop(new \DateTimeImmutable());

        self::assertInstanceOf(TimeEntry::class, $timeEntry);
    }
}
