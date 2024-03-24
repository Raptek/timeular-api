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
}
