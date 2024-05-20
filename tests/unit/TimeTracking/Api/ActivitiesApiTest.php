<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\TimeTracking\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use PsrMock\Psr18\Client;
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
use Timeular\TimeTracking\Api\ActivitiesApi;
use Timeular\TimeTracking\Model\Activity;

#[CoversClass(ActivitiesApi::class)]
#[UsesClass(HttpClientBuilder::class)]
#[UsesClass(RequestFactoryBuilder::class)]
#[UsesClass(SerializerBuilder::class)]
#[UsesClass(HttpClient::class)]
#[UsesClass(MediaTypeResolver::class)]
#[UsesClass(RequestFactory::class)]
#[UsesClass(ResponseHandler::class)]
#[UsesClass(JsonEncoder::class)]
#[UsesClass(Serializer::class)]
#[UsesClass(Activity::class)]
class ActivitiesApiTest extends TestCase
{
    private ActivitiesApi $api;
    private ClientInterface $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->api = new ActivitiesApi((new HttpClientBuilder())->withPsrClient($this->client)->build());
    }

    #[Test]
    public function it_returns_list_of_activities(): void
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
  "activities": [
    {
      "id": "1",
      "name": "abc",
      "color": "#000000",
      "integration": "zei",
      "spaceId": "1",
      "deviceSide": null
    }
  ],
  "inactiveActivities": [
    {
      "id": "2",
      "name": "def",
      "color": "#000000",
      "integration": "zei",
      "spaceId": "2"
    }
  ],
  "archivedActivities": [
    {
      "id": "3",
      "name": "ghi",
      "color": "#000000",
      "integration": "zei",
      "spaceId": "2"
    }
  ]
}
BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/activities', $response);

        $data = $this->api->list();

        self::assertIsArray($data);
        self::assertIsArray($data['activities']);
        self::assertIsArray($data['inactiveActivities']);
        self::assertIsArray($data['archivedActivities']);
        self::assertContainsOnlyInstancesOf(Activity::class, $data['activities']);
        self::assertContainsOnlyInstancesOf(Activity::class, $data['inactiveActivities']);
        self::assertContainsOnlyInstancesOf(Activity::class, $data['archivedActivities']);
    }

    #[Test]
    public function it_creates_activity(): void
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
  "name": "sleeping",
  "color": "#a1b2c3",
  "integration": "zei",
  "spaceId": "1",
  "deviceSide": null
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/activities', $response);

        $activity = $this->api->create('sleeping', '#a1b2c3', 'zei', '1');

        self::assertInstanceOf(Activity::class, $activity);
    }

    #[Test]
    public function it_edits_activity(): void
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
  "name": "deeper sleeping",
  "color": "#f9e8d7",
  "integration": "zei",
  "spaceId": "1"
}
BODY,
            ))
        ;
        $this->client->addResponse('PATCH', RequestFactoryInterface::BASE_URI . '/activities/1', $response);

        $activity = $this->api->edit('1', 'sleeping', '#a1b2c3');

        self::assertInstanceOf(Activity::class, $activity);
    }

    #[Test]
    public function it_archives_activity(): void
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
  "errors": [
    "Any error which can be ignored and did not prevented action to be performed successfully."
  ]
}
BODY,
            ))
        ;
        $this->client->addResponse('DELETE', RequestFactoryInterface::BASE_URI . '/activities/1', $response);

        $activity = $this->api->archive('1');

        self::assertIsArray($activity);
    }

    #[Test]
    public function it_assigns_device_side(): void
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
  "id": "123",
  "name": "sleeping",
  "color": "#a1b2c3",
  "integration": "zei",
  "deviceSide": 4,
  "spaceId": "1"
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/activities/123/device-side/4', $response);

        $activity = $this->api->assign('123', 4);

        self::assertInstanceOf(Activity::class, $activity);
    }

    #[Test]
    public function it_unassigns_device_side(): void
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
  "id": "123",
  "name": "sleeping",
  "color": "#a1b2c3",
  "integration": "zei",
  "deviceSide": null,
  "spaceId": "1"
}
BODY,
            ))
        ;
        $this->client->addResponse('DELETE', RequestFactoryInterface::BASE_URI . '/activities/123/device-side/4', $response);

        $activity = $this->api->unassign('123', 4);

        self::assertInstanceOf(Activity::class, $activity);
    }
}
