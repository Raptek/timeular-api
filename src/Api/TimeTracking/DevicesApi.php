<?php

declare(strict_types=1);

namespace Timeular\Api\TimeTracking;

use Timeular\Http\HttpClient;
use Timeular\Model\Device;

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

        return array_map(static fn (array $device): Device => Device::fromArray($device), $response['devices']);
    }

    /**
     * @see https://developers.timeular.com/#2d3946a1-d112-443b-8058-0b27f3fde396
     */
    public function activate(string $serial): Device
    {
        $response = $this->httpClient->request(
            'POST',
            sprintf('devices/%s/activate', $serial),
        );

        return Device::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#59928e50-d695-4118-8d71-13079f4ae9d9
     */
    public function deactivate(string $serial): Device
    {
        $response = $this->httpClient->request(
            'POST',
            sprintf('devices/%s/deactivate', $serial),
        );

        return Device::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#78ab7505-587f-469a-974f-781647bc4900
     */
    public function edit(string $serial, string $name): Device
    {
        $response = $this->httpClient->request(
            'PATCH',
            sprintf('devices/%s', $serial),
            [
                'name' => $name,
            ]
        );

        return Device::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#08024987-8f56-41d4-8653-97cbf1202809
     */
    public function forget(string $serial): void
    {
        $this->httpClient->request(
            'DELETE',
            sprintf('devices/%s', $serial),
        );
    }

    /**
     * @see https://developers.timeular.com/#985dae45-b3db-4993-a4b1-5847044388bd
     */
    public function disable(string $serial): Device
    {
        $response = $this->httpClient->request(
            'POST',
            sprintf('devices/%s/disable', $serial),
        );

        return Device::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#96f1eb5b-5aa6-43eb-9176-fd8b7bd5b16f
     */
    public function enable(string $serial): Device
    {
        $response = $this->httpClient->request(
            'POST',
            sprintf('devices/%s/enable', $serial),
        );

        return Device::fromArray($response);
    }
}
