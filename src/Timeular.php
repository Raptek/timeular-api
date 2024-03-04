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

    public function signIn(): string
    {
        $response = $this->httpClient->request(
            'POST',
            'developer/sign-in',
            [
                'apiKey' => $this->apiKey,
                'apiSecret' => $this->apiSecret,
            ]
        );

        return json_decode($response->getBody()->getContents())->token;
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

    public function me(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'me',
            [],
            [
                'Authorization' => sprintf('Bearer %s', $this->getToken()),
            ]
        );

        return json_decode($response->getBody()->getContents(), true)['data'];
    }
}
