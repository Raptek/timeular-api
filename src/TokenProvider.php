<?php

declare(strict_types=1);

namespace Timeular;

use Psr\SimpleCache\CacheInterface;
use Timeular\Http\Client;

class TokenProvider
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

    public function get(): string
    {
        $token = $this->cache->get(self::CACHE_KEY);

        if (null === $token) {
            $this->set();
        }

        return $this->cache->get(self::CACHE_KEY);
    }

    public function set(): void
    {
        $response = $this->httpClient->request(
            'POST',
            'developer/sign-in',
            [
                'apiKey' => $this->apiKey,
                'apiSecret' => $this->apiSecret,
            ],
        );

        $token = $response['token'];

        $this->cache->set(self::CACHE_KEY, $token, 300);
    }

    public function clear(): void
    {
        $this->cache->delete(self::CACHE_KEY);
    }
}
