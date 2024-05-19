<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Api;

use Timeular\Http\HttpClient;
use Timeular\TimeTracking\Model\Duration;
use Timeular\TimeTracking\Model\TimeEntry;

readonly class TimeEntriesApi
{
    public function __construct(
        private HttpClient $httpClient,
    ) {}

    /**
     * @see https://developers.timeular.com/#d4c6e3c4-c38b-4891-aa19-907460f43f9b
     */
    public function find(\DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt): array
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('time-entries/%s/%s', $startedAt->format(Duration::FORMAT), $stoppedAt->format(Duration::FORMAT)),
        );

        return $response['timeEntries'];
    }

    /**
     * @see https://developers.timeular.com/#e66a9e5a-1035-4522-a9fc-5df5a5a05ef7
     */
    public function create(string $activityId, \DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt, string|null $note): TimeEntry
    {
        $response = $this->httpClient->request(
            'POST',
            'time-entries',
            [
                'activityId' => $activityId,
                'startedAt' => $startedAt->format(Duration::FORMAT),
                'stoppedAt' => $stoppedAt->format(Duration::FORMAT),
                'note' => $note ? ['text' => $note] : null,
            ],
        );

        return TimeEntry::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#b4c0569e-a8a7-4c11-9b82-d091bf656812
     */
    public function findById(string $id): TimeEntry
    {
        $response = $this->httpClient->request(
            'GET',
            sprintf('time-entries/%s', $id),
        );

        return TimeEntry::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#18d45e78-35f7-4dc2-a6c4-edb2405014ed
     */
    public function edit(string $id, string $activityId, \DateTimeInterface $startedAt, \DateTimeInterface $stoppedAt, string|null $note): TimeEntry
    {
        $response = $this->httpClient->request(
            'PATCH',
            sprintf('time-entries/%s', $id),
            [
                'activityId' => $activityId,
                'startedAt' => $startedAt->format(Duration::FORMAT),
                'stoppedAt' => $stoppedAt->format(Duration::FORMAT),
                'note' => $note ? ['text' => $note] : null,
            ],
        );

        return TimeEntry::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#a987147c-7c11-4fdc-9ca5-7b03e0999199
     */
    public function delete(string $id): array
    {
        return $this->httpClient->request(
            'DELETE',
            sprintf('time-entries/%s', $id),
        );
    }
}
