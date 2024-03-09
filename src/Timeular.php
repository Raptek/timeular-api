<?php

declare(strict_types=1);

namespace Timeular;

use Psr\SimpleCache\CacheInterface;
use Timeular\Http\Client;

class Timeular
{
    private const string CACHE_KEY = 'timeular-token';

    private Client $httpClient;

    public function __construct(
        private string $apiKey,
        private string $apiSecret,
        private CacheInterface $cache,
        ?Client $httpClient = null,
    ) {
        $this->httpClient = $httpClient ?? new Client('https://api.timeular.com/api/v3');
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
                'Authorization' => sprintf('Bearer %s', $this->getToken()),
            ],
        );
    }

    private function getToken(): string
    {
        $token = $this->cache->get(self::CACHE_KEY);

        if (null === $token) {
            $token = $this->signIn();

            $this->cache->set(self::CACHE_KEY, $token, 300);
        }

        return $token;
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
                'Authorization' => sprintf('Bearer %s', $this->getToken()),
            ],
        );

        return $response['data'];
    }
}
