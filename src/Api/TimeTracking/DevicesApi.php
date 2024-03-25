<?php

declare(strict_types=1);

namespace Timeular\Api\TimeTracking;

use Timeular\Http\HttpClient;

class DevicesApi
{
    public function __construct(
        private HttpClient $httpClient,
    ) {
    }

    /**
     * @see https://developers.timeular.com/#bbf459e2-ff90-4aeb-b064-7febaa4eba70
     */
    public function list(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'devices',
        );

        return $response['devices'];
    }

    /**
     * @see https://developers.timeular.com/#2d3946a1-d112-443b-8058-0b27f3fde396
     */
    public function activate(string $id): array
    {
        $response = $this->httpClient->request(
            'POST',
            sprintf('devices/%s/activate', $id),
        );

        return $response;
    }

    /**
     * @see https://developers.timeular.com/#59928e50-d695-4118-8d71-13079f4ae9d9
     */
    public function deactivate(string $id): array
    {
        $response = $this->httpClient->request(
            'POST',
            sprintf('devices/%s/deactivate', $id),
        );

        return $response;
    }

    /**
     * @see https://developers.timeular.com/#78ab7505-587f-469a-974f-781647bc4900
     */
    public function edit(string $id, string $name): array
    {
        $response = $this->httpClient->request(
            'PATCH',
            sprintf('devices/%s', $id),
            [
                'name' => $name,
            ]
        );

        return $response;
    }

    /**
     * @see https://developers.timeular.com/#08024987-8f56-41d4-8653-97cbf1202809
     */
    public function forget(string $id): void
    {
        $this->httpClient->request(
            'DELETE',
            sprintf('devices/%s', $id),
        );
    }

    /**
     * @see https://developers.timeular.com/#985dae45-b3db-4993-a4b1-5847044388bd
     */
    public function disable(string $id): array
    {
        $response = $this->httpClient->request(
            'POST',
            sprintf('devices/%s/disable', $id),
        );

        return $response;
    }

    /**
     * @see https://developers.timeular.com/#96f1eb5b-5aa6-43eb-9176-fd8b7bd5b16f
     */
    public function enable(string $id): array
    {
        $response = $this->httpClient->request(
            'POST',
            sprintf('devices/%s/enable', $id),
        );

        return $response;
    }
}
