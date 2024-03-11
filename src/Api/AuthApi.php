<?php

declare(strict_types=1);

namespace Timeular\Api;

use Timeular\Http\Client;

class AuthApi
{
    public function __construct(
        private string $apiKey,
        private string $apiSecret,
        private Client $httpClient,
    ) {
    }

    /**
     * @see https://developers.timeular.com/#12de6e46-4b3a-437b-94b2-39b7782eb24c
     */
    public function signIn(): string
    {
        $response = $this->httpClient->request(
            'POST',
            'developer/sign-in',
            [
                'apiKey' => $this->apiKey,
                'apiSecret' => $this->apiSecret,
            ],
        );

        return $response['token'];
    }

    /**
     * @see https://developers.timeular.com/#b2a18382-8f61-4222-bb5d-fa58c2c260a9
     */
    public function logout(): void
    {
        $this->httpClient->request(
            'POST',
            'developer/logout',
            [],
            [
                'Authorization' => sprintf('Bearer %s', $this->signIn()),
            ],
        );
    }
}
