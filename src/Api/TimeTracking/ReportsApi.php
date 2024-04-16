<?php

declare(strict_types=1);

namespace Timeular\Api\TimeTracking;

use Timeular\Http\HttpClient;
use Timeular\Model\TimeTracking\Duration;
use Timeular\Model\TimeTracking\ReportTimeEntry;

class ReportsApi
{
    public function __construct(
        private HttpClient $httpClient,
    ) {
    }

    public function getAllData(\DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt): array
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('report/data/%s/%s', $startedAt->format(Duration::FORMAT), $stoppedAt->format(Duration::FORMAT)),
        );

        return array_map(static fn (array $timeEntry): ReportTimeEntry => ReportTimeEntry::fromArray($timeEntry), $response['timeEntries']);
    }
}
