<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Api;

use Timeular\Http\HttpClient;
use Timeular\TimeTracking\Exception\TooShortTimeEntryException;
use Timeular\TimeTracking\Model\ActiveTimeEntry;
use Timeular\TimeTracking\Model\TimeEntry;

readonly class CurrentTrackingApi
{
    public function __construct(
        private HttpClient $httpClient,
    ) {}

    /**
     * @see https://developers.timeular.com/#13f48c99-bf92-4892-9f5d-ae17f603526a
     */
    public function show(): ActiveTimeEntry|null
    {
        $response = $this->httpClient->request(
            'GET',
            'tracking',
        );

        return $response['currentTracking'] ? ActiveTimeEntry::fromArray($response['currentTracking']) : null;
    }

    /**
     * @see https://developers.timeular.com/#4d1dcf30-125a-48d3-8895-27e611581f50
     */
    public function start(string $activityId, \DateTimeInterface $startedAt): ActiveTimeEntry
    {
        $response = $this->httpClient->request(
            'POST',
            sprintf('tracking/%s/start', $activityId),
            [
                'startedAt' => $startedAt->format('Y-m-d\TH:i:s.v'),
            ],
        );

        return ActiveTimeEntry::fromArray($response['currentTracking']);
    }

    /**
     * @see https://developers.timeular.com/#52af0d09-fdd8-4095-81bd-d3319cda2c22
     */
    public function edit(string $activityId, \DateTimeInterface|null $startedAt = null, string|null $note = null): ActiveTimeEntry
    {
        $payload = [
            'note' => $note ? ['text' => $note] : null,
            'activity' => $activityId,
        ];

        if (null !== $startedAt) {
            $payload['startedAt'] = $startedAt->format('Y-m-d\TH:i:s.v');
        }

        $response = $this->httpClient->request(
            'PATCH',
            'tracking',
            $payload,
        );

        return ActiveTimeEntry::fromArray($response['currentTracking']);
    }

    /**
     * @see https://developers.timeular.com/#329c8b25-a27f-41f9-bdd6-8db04627f0ea
     */
    public function stop(\DateTimeInterface $stoppedAt): TimeEntry
    {
        $response = $this->httpClient->request(
            'POST',
            'tracking/stop',
            [
                'stoppedAt' => $stoppedAt->format('Y-m-d\TH:i:s.v'),
            ],
        );

        if (array_key_exists('error', $response) && $response['error']['code'] === '00400100001') {
            throw new TooShortTimeEntryException();
        }

        return TimeEntry::fromArray($response['createdTimeEntry']);
    }
}
