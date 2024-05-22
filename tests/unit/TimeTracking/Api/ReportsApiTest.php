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
use Timeular\Http\Builder\HttpClientBuilder;
use Timeular\Http\Builder\RequestFactoryBuilder;
use Timeular\Http\Builder\Serializer\SerializerBuilder;
use Timeular\Http\HttpClient;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\ResponseHandler;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\PassthroughEncoder;
use Timeular\Http\Serializer\Serializer;
use Timeular\TimeTracking\Api\ReportsApi;
use Timeular\TimeTracking\Model\Activity;
use Timeular\TimeTracking\Model\Duration;
use Timeular\TimeTracking\Model\Mention;
use Timeular\TimeTracking\Model\Note;
use Timeular\TimeTracking\Model\ReportTimeEntry;

#[CoversClass(ReportsApi::class)]
#[UsesClass(HttpClientBuilder::class)]
#[UsesClass(RequestFactoryBuilder::class)]
#[UsesClass(SerializerBuilder::class)]
#[UsesClass(HttpClient::class)]
#[UsesClass(MediaTypeResolver::class)]
#[UsesClass(RequestFactory::class)]
#[UsesClass(ResponseHandler::class)]
#[UsesClass(JsonEncoder::class)]
#[UsesClass(PassthroughEncoder::class)]
#[UsesClass(Serializer::class)]
#[UsesClass(Activity::class)]
#[UsesClass(Duration::class)]
#[UsesClass(Mention::class)]
#[UsesClass(Note::class)]
#[UsesClass(ReportTimeEntry::class)]
class ReportsApiTest extends TestCase
{
    private ReportsApi $api;
    private ClientContract $client;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->api = new ReportsApi((new HttpClientBuilder())->withPsrClient($this->client)->build());
    }

    #[Test]
    public function it_returns_all_data(): void
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
      "creator": "1",
      "activity": {
        "id": "1",
        "name": "sleeping",
        "color": "#000000",
        "integration": "zei",
        "spaceId": "1"
      },
      "duration": {
        "startedAt": "2017-02-06T17:25:00.000",
        "stoppedAt": "2017-02-06T18:25:00.000"
      },
      "note": {
        "text": "<{{|m|1|}}> some text",
        "tags": [],
        "mentions": [
          {
            "id": 1,
            "key": "123432234",
            "label": "some-mention",
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
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/report/data/2016-01-01T00:00:00.000/2017-12-31T23:59:59.999', $response);

        $timeEntries = $this->api->getAllData(new \DateTimeImmutable('2016-01-01T00:00:00.000'), new \DateTimeImmutable('2017-12-31T23:59:59.999'));

        self::assertIsArray($timeEntries);
        self::assertContainsOnlyInstancesOf(ReportTimeEntry::class, $timeEntries);
    }

    #[Test]
    public function it_generates_report(): void
    {
        $authorizationResponse = (new Response(200))
            ->withHeader('Content-Type', 'application/json')
            ->withBody(new Stream(json_encode(['token' => 'token'])))
        ;
        $this->client->addResponse('POST', RequestFactoryInterface::BASE_URI . '/developer/sign-in', $authorizationResponse);

        $response = (new Response(200))
            ->withHeader('Content-Type', 'text/csv')
            ->withBody(new Stream(
                <<<BODY
"Version","TimeEntryID","StartDate","StartTime","StartTimeOffset","EndDate","EndTime","EndTimeOffset","Duration","ActivityID","Activity","SpaceId","Space","Username","Note","Mentions","Tags"
"4","34714342","2016-02-03","05:00:00","+0100","2016-02-03","06:00:00","+0100","01:00:00","116080","sleeping","1","My Space","my-email@timeular.com","99 sheep","",""
"4","34714421","2016-08-05","08:00:00","+0200","2016-08-05","09:00:00","+0200","01:00:00","1217348","sleeping","2","Shared Space","my-email@timeular.com","99 sheep #my new tag4 @Salaidh","@Salaidh","#my new tag4"
"4","31623071","2017-02-06","18:25:00","+0100","2017-02-06","19:25:00","+0100","01:00:00","876519","ZEIº-Test","1","My Space","my-email@timeular.com","@ZEIT-13","@ZEIT-13",""

BODY,
            ))
        ;
        $this->client->addResponse('GET', RequestFactoryInterface::BASE_URI . '/report/2016-01-01T00:00:00.000/2017-12-31T23:59:59.999?timezone=Europe%2FWarsaw&fileType=csv', $response);

        $report = $this->api->generateReport(
            new \DateTimeImmutable('2016-01-01T00:00:00.000'),
            new \DateTimeImmutable('2017-12-31T23:59:59.999'),
            new \DateTimeZone('Europe/Warsaw'),
        );

        self::assertIsString($report);
    }
}
