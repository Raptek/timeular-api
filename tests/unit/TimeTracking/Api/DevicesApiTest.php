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
use Timeular\TimeTracking\Api\DevicesApi;
use Timeular\TimeTracking\Model\Device;

#[CoversClass(DevicesApi::class)]
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
#[UsesClass(Device::class)]
#[UsesClass(AuthApi::class)]
class DevicesApiTest extends TestCase
{
    private DevicesApi $api;
    private ClientContract $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->api = new DevicesApi((new HttpClientFactory($this->client))->create());
    }

    #[Test]
    public function it_returns_list_of_devices(): void
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
  "devices": [
    {
      "serial": "123",
      "name": "Personal Tracker",
      "active": true,
      "disabled": false
    }
  ]
}
BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/devices', $response);

        $devices = $this->api->list();

        self::assertIsArray($devices);
        self::assertContainsOnlyInstancesOf(Device::class, $devices);
    }

    #[Test]
    public function it_activates_device(): void
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
  "serial": "123",
  "name": "Personal Tracker",
  "active": true,
  "disabled": false
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/devices/123/activate', $response);

        $device = $this->api->activate('123');

        self::assertInstanceOf(Device::class, $device);
    }

    #[Test]
    public function it_deactivates_device(): void
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
  "serial": "123",
  "name": "Personal Tracker",
  "active": true,
  "disabled": false
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/devices/123/deactivate', $response);

        $device = $this->api->deactivate('123');

        self::assertInstanceOf(Device::class, $device);
    }

    #[Test]
    public function it_edits_device(): void
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
  "serial": "123",
  "name": "Personal Tracker",
  "active": true,
  "disabled": false
}
BODY,
            ))
        ;
        $this->client->addResponse('PATCH', RequestFactoryInterface::BASE_URI . '/devices/123', $response);

        $device = $this->api->edit('123', 'Personal Tracker');

        self::assertInstanceOf(Device::class, $device);
    }

    #[Test]
    public function it_disables_device(): void
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
  "serial": "123",
  "name": "Personal Tracker",
  "active": true,
  "disabled": false
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/devices/123/disable', $response);

        $device = $this->api->disable('123');

        self::assertInstanceOf(Device::class, $device);
    }

    #[Test]
    public function it_enables_device(): void
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
  "serial": "123",
  "name": "Personal Tracker",
  "active": true,
  "disabled": false
}
BODY,
            ))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/devices/123/enable', $response);

        $device = $this->api->enable('123');

        self::assertInstanceOf(Device::class, $device);
    }
}
