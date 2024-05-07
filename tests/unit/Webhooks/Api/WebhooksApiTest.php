<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Webhooks\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use PsrMock\Psr18\Client;
use PsrMock\Psr7\Response;
use PsrMock\Psr7\Stream;
use Tests\Builders\Timeular\Http\HttpClientBuilder;
use Timeular\Http\HttpClient;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\ResponseHandler;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\Serializer;
use Timeular\Webhooks\Api\WebhooksApi;
use Timeular\Webhooks\Model\Event;

#[CoversClass(WebhooksApi::class)]
#[UsesClass(HttpClient::class)]
#[UsesClass(MediaTypeResolver::class)]
#[UsesClass(RequestFactory::class)]
#[UsesClass(ResponseHandler::class)]
#[UsesClass(JsonEncoder::class)]
#[UsesClass(Serializer::class)]
class WebhooksApiTest extends TestCase
{
    private WebhooksApi $api;
    private ClientInterface $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->api = new WebhooksApi((new HttpClientBuilder())->withPsrClient($this->client)->build());
    }

    #[Test]
    public function it_returns_list_of_events(): void
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
  "events": [
    "timeEntryCreated",
    "timeEntryUpdated",
    "timeEntryDeleted",
    "trackingStarted",
    "trackingStopped",
    "trackingEdited",
    "trackingCanceled"
  ]
}
BODY
                       ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/webhooks/event', $response);

        $events = $this->api->listAvailableEvents();

        self::assertIsArray($events);
        self::assertContainsOnlyInstancesOf(Event::class, $events);
    }
}
