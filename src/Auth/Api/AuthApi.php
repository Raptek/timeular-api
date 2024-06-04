<?php

declare(strict_types=1);

namespace Timeular\Auth\Api;

use Timeular\Http\HttpClientInterface;

readonly class AuthApi
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    /**
     * @see https://developers.timeular.com/#12de6e46-4b3a-437b-94b2-39b7782eb24c
     */
    public function signIn(string $apiKey, string $apiSecret): string
    {
        $response = $this->httpClient->request(
            'POST',
            'developer/sign-in',
            [
                'apiKey' => $apiKey,
                'apiSecret' => $apiSecret,
            ],
        );

        return $response['token'];
    }

    /**
     * @see https://developers.timeular.com/#2cc9aa7c-c235-4b2d-a1b5-7587167bd542
     */
    public function fetchApiKey(): string
    {
        $response = $this->httpClient->request(
            'GET',
            'developer/api-access',
        );

        return $response['apiKey'];
    }

    /**
     * @see https://developers.timeular.com/#e1db0328-fad2-4679-82c6-c16a89130fce
     */
    public function regenerateKeyPair(): array
    {
        return $this->httpClient->request(
            'POST',
            'developer/api-access',
        );
    }

    /**
     * @see https://developers.timeular.com/#b2a18382-8f61-4222-bb5d-fa58c2c260a9
     */
    public function logout(): void
    {
        $this->httpClient->request(
            'POST',
            'developer/logout',
        );
    }
}
