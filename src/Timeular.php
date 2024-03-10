<?php

declare(strict_types=1);

namespace Timeular;

use Timeular\Http\Client;

class Timeular
{
    private Client $httpClient;

    public function __construct(
        private TokenProvider $tokenProvider,
        ?Client $httpClient = null,
    ) {
        $this->httpClient = $httpClient ?? new Client('https://api.timeular.com/api/v3');
    }

    /**
     * @see https://developers.timeular.com/#12de6e46-4b3a-437b-94b2-39b7782eb24c
     */
    public function signIn(): string
    {
        $this->tokenProvider->clear();

        return $this->tokenProvider->get();
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
                'Authorization' => sprintf('Bearer %s', $this->tokenProvider->get()),
            ],
        );

        $this->tokenProvider->clear();
    }

    /**
     * @see https://developers.timeular.com/#bbf459e2-ff90-4aeb-b064-7febaa4eba70
     */
    public function me(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'me',
            [],
            [
                'Authorization' => sprintf('Bearer %s', $this->tokenProvider->get()),
            ],
        );

        return $response['data'];
    }
}
