<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Api;

use Timeular\Http\Exception\BadRequestException;
use Timeular\Http\Exception\ConflictException;
use Timeular\Http\Exception\NotFoundException;
use Timeular\Http\HttpClientInterface;
use Timeular\TimeTracking\Exception\DeviceNameTooLongException;
use Timeular\TimeTracking\Exception\DeviceNotFoundException;
use Timeular\TimeTracking\Exception\InactiveDeviceException;
use Timeular\TimeTracking\Model\Device;

readonly class DevicesApi
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    /**
     * @see https://developers.timeular.com/#bbf459e2-ff90-4aeb-b064-7febaa4eba70
     */
    public function list(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'devices',
        );

        return array_map(static fn(array $device): Device => Device::fromArray($device), $response['devices']);
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
     *
     * @throws DeviceNotFoundException
     * @throws InactiveDeviceException
     */
    public function deactivate(string $serial): Device
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                sprintf('devices/%s/deactivate', $serial),
            );
        } catch (NotFoundException) {
            throw DeviceNotFoundException::fromSerial($serial);
        } catch (ConflictException) {
            throw InactiveDeviceException::fromSerial($serial);
        }

        return Device::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#78ab7505-587f-469a-974f-781647bc4900
     *
     * @throws DeviceNotFoundException
     * @throws DeviceNameTooLongException
     */
    public function edit(string $serial, string $name): Device
    {
        try {
            $response = $this->httpClient->request(
                'PATCH',
                sprintf('devices/%s', $serial),
                [
                    'name' => $name,
                ],
            );
        } catch (NotFoundException) {
            throw DeviceNotFoundException::fromSerial($serial);
        } catch (BadRequestException) {
            throw DeviceNameTooLongException::fromName($name);
        }

        return Device::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#08024987-8f56-41d4-8653-97cbf1202809
     *
     * @throws DeviceNotFoundException
     */
    public function forget(string $serial): void
    {
        try {
            $this->httpClient->request(
                'DELETE',
                sprintf('devices/%s', $serial),
            );
        } catch (NotFoundException) {
            throw DeviceNotFoundException::fromSerial($serial);
        }
    }

    /**
     * @see https://developers.timeular.com/#985dae45-b3db-4993-a4b1-5847044388bd
     *
     * @throws DeviceNotFoundException
     */
    public function disable(string $serial): Device
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                sprintf('devices/%s/disable', $serial),
            );
        } catch (NotFoundException) {
            throw DeviceNotFoundException::fromSerial($serial);
        }

        return Device::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#96f1eb5b-5aa6-43eb-9176-fd8b7bd5b16f
     *
     * @throws DeviceNotFoundException
     */
    public function enable(string $serial): Device
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                sprintf('devices/%s/enable', $serial),
            );
        } catch (NotFoundException) {
            throw DeviceNotFoundException::fromSerial($serial);
        }

        return Device::fromArray($response);
    }
}
