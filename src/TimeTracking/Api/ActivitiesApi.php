<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Api;

use Timeular\Http\Exception\AccessDeniedException;
use Timeular\Http\Exception\BadRequestException;
use Timeular\Http\HttpClientInterface;
use Timeular\TimeTracking\Exception\InvalidColorException;
use Timeular\TimeTracking\Exception\InvalidIntegrationException;
use Timeular\TimeTracking\Exception\NotSpaceAdminException;
use Timeular\TimeTracking\Exception\ThirdPartyIntegrationException;
use Timeular\TimeTracking\Model\Activity;

readonly class ActivitiesApi
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

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
            'activities' => array_map(static fn(array $activity): Activity => Activity::fromArray($activity), $response['activities']),
            'inactiveActivities' => array_map(static fn(array $activity): Activity => Activity::fromArray($activity), $response['inactiveActivities']),
            'archivedActivities' => array_map(static fn(array $activity): Activity => Activity::fromArray($activity), $response['archivedActivities']),
        ];
    }

    /**
     * @see https://developers.timeular.com/#591f7ca0-7ec5-4c0e-b0d0-99b6967ce53e
     *
     * @throws NotSpaceAdminException
     */
    public function create(string $name, string $color, string $integration, string $spaceId): Activity
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                'activities',
                [
                    'name' => $name,
                    'color' => $color,
                    'integration' => $integration,
                    'spaceId' => $spaceId,
                ],
            );
        } catch (AccessDeniedException) {
            throw NotSpaceAdminException::create();
        } catch (BadRequestException $exception) {
            throw match ($exception->getMessage()) {
                "Activity Color is not in hexadecimal representation ('#' followed by 3 or 6 characters, eg. '#a0b2f9'" => InvalidColorException::fromColor($color),
                "Activity Integration is invalid: valid Activity Integration has from 1 to 50 characters and contains only 'a'-'z', 'A'-'Z', '0'-'9', '-', '_', or '.' (examples: 'jira', 'my.hosted.harvest')" => InvalidIntegrationException::fromIntegration($integration),
                'Third Party Integrations are not allowed in shared spaces - please use your personal space.' => ThirdPartyIntegrationException::create(),
            };
        }

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
            ],
        );

        return Activity::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#234c5874-2086-4104-bff7-af9b9efeced8
     */
    public function archive(string $id): array
    {
        return $this->httpClient->request(
            'DELETE',
            sprintf('activities/%s', $id),
        );
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
