<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Api;

use Timeular\Http\HttpClient;
use Timeular\TimeTracking\Model\Activity;

class ActivitiesApi
{
    public function __construct(
        private HttpClient $httpClient,
    ) {
    }

    /**
     * @see https://developers.timeular.com/#9ac8c381-7e91-4802-8f02-e6918493e902
     */
    public function list(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'activities',
        );

        return [
            'activities' => array_map(static fn (array $activity): Activity => Activity::fromArray($activity), $response['activities']),
            'inactiveActivities' => array_map(static fn (array $activity): Activity => Activity::fromArray($activity), $response['inactiveActivities']),
            'archivedActivities' => array_map(static fn (array $activity): Activity => Activity::fromArray($activity), $response['archivedActivities']),
        ];
    }

    /**
     * @see https://developers.timeular.com/#591f7ca0-7ec5-4c0e-b0d0-99b6967ce53e
     */
    public function create(string $name, string $color, string $integration, string $spaceId): Activity
    {
        $response = $this->httpClient->request(
            'POST',
            'activities',
            [
                'name' => $name,
                'color' => $color,
                'integration' => $integration,
                'spaceId' => $spaceId,
            ]
        );

        return Activity::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#1ac62610-1bb7-411c-846b-c9690fa3ace5
     */
    public function edit(string $id, string $name, string $color): Activity
    {
        $response = $this->httpClient->request(
            'PATCH',
            sprintf('activities/%s', $id),
            [
                'name' => $name,
                'color' => $color,
            ]
        );

        return Activity::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#234c5874-2086-4104-bff7-af9b9efeced8
     */
    public function archive(string $id): array
    {
        $response = $this->httpClient->request(
            'DELETE',
            sprintf('activities/%s', $id),
        );

        return $response;
    }

    /**
     * @see https://developers.timeular.com/#8307c8c6-d1d0-476b-abcf-cf76c3d319c0
     */
    public function assign(string $id, int $deviceSide): Activity
    {
        $response = $this->httpClient->request(
            'POST',
            sprintf('activities/%s/device-side/%d', $id, $deviceSide),
        );

        return Activity::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#583e3518-e1df-4a9f-8af7-83efbdd6e79b
     */
    public function unassign(string $id, int $deviceSide): Activity
    {
        $response = $this->httpClient->request(
            'DELETE',
            sprintf('activities/%s/device-side/%d', $id, $deviceSide),
        );

        return Activity::fromArray($response);
    }
}
