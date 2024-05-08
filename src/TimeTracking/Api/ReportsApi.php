<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Api;

use Timeular\Http\HttpClient;
use Timeular\TimeTracking\Model\Duration;
use Timeular\TimeTracking\Model\ReportTimeEntry;

readonly class ReportsApi
{
    public function __construct(
        private HttpClient $httpClient,
    ) {
    }

    /**
     * @see https://developers.timeular.com/#e12c5e47-8f39-4984-b757-798dcbbf2365
     */
    public function getAllData(\DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt): array
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('report/data/%s/%s', $startedAt->format(Duration::FORMAT), $stoppedAt->format(Duration::FORMAT)),
        );

        return array_map(static fn (array $timeEntry): ReportTimeEntry => ReportTimeEntry::fromArray($timeEntry), $response['timeEntries']);
    }

    /**
     * @see https://developers.timeular.com/#f9bed9f5-6fbe-4062-9881-76b117430eb2
     */
    public function generateReport(\DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt, string $timezone, string|null $activityId = null, string|null $noteQuery = null, string|null $fileType = 'csv'): string
    {
        $queryData = [
            'timezone' => $timezone,
            'activityId' => $activityId,
            'noteQuery' => $noteQuery,
            'fileType' => $fileType,
        ];

        $query = http_build_query(array_filter($queryData));

        $response = $this->httpClient->request(
            'GET',
            sprintf('report/%s/%s?%s', $startedAt->format(Duration::FORMAT), $stoppedAt->format(Duration::FORMAT), $query),
        );

        return $response;
    }
}
