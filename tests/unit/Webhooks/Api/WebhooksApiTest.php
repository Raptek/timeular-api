<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular\Webhooks\Api;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use PsrMock\Psr18\Client;
use PsrMock\Psr18\Contracts\ClientContract;
use PsrMock\Psr7\Response;
use PsrMock\Psr7\Stream;
use Tests\Unit\Timeular\HttpClientFactory;
use Timeular\Auth\Api\AuthApi;
use Timeular\Http\Exception\BadRequestException;
use Timeular\Http\Exception\HttpException;
use Timeular\Http\Exception\NotFoundException;
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
use Timeular\Webhooks\Api\WebhooksApi;
use Timeular\Webhooks\Exception\InvalidEventException;
use Timeular\Webhooks\Exception\InvalidUrlException;
use Timeular\Webhooks\Exception\MaximumSubscriptionsReachedException;
use Timeular\Webhooks\Exception\SubscriptionNotFoundException;
use Timeular\Webhooks\Exception\WebhooksException;
use Timeular\Webhooks\Model\Event;
use Timeular\Webhooks\Model\Subscription;

#[CoversClass(WebhooksApi::class)]
#[UsesClass(HttpClient::class)]
#[UsesClass(MediaTypeResolver::class)]
#[UsesClass(RequestFactory::class)]
#[UsesClass(ResponseHandler::class)]
#[UsesClass(JsonEncoder::class)]
#[UsesClass(Serializer::class)]
#[UsesClass(MediaTypeResolverFactory::class)]
#[UsesClass(RequestFactoryFactory::class)]
#[UsesClass(ResponseHandlerFactory::class)]
#[UsesClass(SerializerFactory::class)]
#[UsesClass(Subscription::class)]
#[UsesClass(AuthApi::class)]
#[UsesClass(HttpException::class)]
#[UsesClass(BadRequestException::class)]
#[UsesClass(InvalidEventException::class)]
#[UsesClass(InvalidUrlException::class)]
#[UsesClass(MaximumSubscriptionsReachedException::class)]
#[UsesClass(NotFoundException::class)]
#[UsesClass(SubscriptionNotFoundException::class)]
class WebhooksApiTest extends TestCase
{
    private WebhooksApi $api;
    private ClientContract $client;

    public static function subscriptionExceptions(): \Generator
    {
        yield 'invalid event provided' => [
            new Stream(
                <<<BODY
{
  "message": "invalid event provided",
  "status": "400 Bad Request"
}
BODY,
            ),
            InvalidEventException::fromEvent('test'),
        ];
        yield 'invalid URL provided' => [
            new Stream(
                <<<BODY
{
  "message": "invalid URL provided",
  "status": "400 Bad Request"
}
BODY,
            ),
            InvalidUrlException::fromUrl('test'),
        ];
        yield 'maximum subscriptions per event exceeded' => [
            new Stream(
                <<<BODY
{
  "message": "maximum subscriptions per event exceeded",
  "status": "400 Bad Request"
}
BODY,
            ),
            MaximumSubscriptionsReachedException::fromEvent('test'),
        ];
        yield 'unexpected message' => [
            new Stream(
                <<<BODY
{
  "message": "unexpected message",
  "status": "400 Bad Request"
}
BODY,
            ),
            BadRequestException::withMessage('unexpected message'),
        ];
    }

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->api = new WebhooksApi((new HttpClientFactory($this->client))->create());
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
BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/webhooks/event', $response);

        $events = $this->api->listAvailableEvents();

        self::assertIsArray($events);
        self::assertContainsOnlyInstancesOf(Event::class, $events);
    }

    #[Test]
    public function it_subscribes(): void
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
  "id": "123456"
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/webhooks/subscription', $response);

        $subscription = $this->api->subscribe('trackingStarted', 'https://example.org/some-endpoint');

        self::assertInstanceOf(Subscription::class, $subscription);
        self::assertSame('123456', $subscription->id);
        self::assertSame(Event::from('trackingStarted'), $subscription->event);
        self::assertSame('https://example.org/some-endpoint', $subscription->targetUrl);
    }

    #[Test]
    #[DataProvider('subscriptionExceptions')]
    public function it_handles_exceptions_during_subscribing(Stream $body, WebhooksException|BadRequestException $exception): void
    {
        $authorizationResponse = (new Response(200))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(json_encode(['token' => 'token'])))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/developer/sign-in', $authorizationResponse);

        $response = (new Response(400))
            ->withHeader('Content-Type', 'application/json')
            ->withBody($body)
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/webhooks/subscription', $response);

        self::expectException($exception::class);
        self::expectExceptionMessage($exception->getMessage());

        $this->api->subscribe('test', 'test');
    }

    #[Test]
    public function it_handles_exceptions_during_unsubscribing(): void
    {
        $authorizationResponse = (new Response(200))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(json_encode(['token' => 'token'])))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/developer/sign-in', $authorizationResponse);

        $response = (new Response(404))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(
                <<<BODY
{
  "message": "could not find subscription with id 123456",
  "status": "404 Not Found"
}
BODY,
            ))
        ;
        $this->client->addResponse('DELETE', RequestFactoryInterface::BASE_URI . '/webhooks/subscription/123456', $response);

        self::expectException(SubscriptionNotFoundException::class);
        self::expectExceptionMessage('Subscription with id "123456" could not be found.');

        $this->api->unsubscribe('123456');
    }

    #[Test]
    public function it_lists_subscriptions(): void
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
  "subscriptions": [
    {
      "id": "123456",
      "event": "trackingStarted",
      "target_url": "https://example.org/some-endpoint"
    }
  ]
}
BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/webhooks/subscription', $response);

        $subscriptions = $this->api->listSubscriptions();

        self::assertContainsOnlyInstancesOf(Subscription::class, $subscriptions);
        self::assertCount(1, $subscriptions);
    }
}
