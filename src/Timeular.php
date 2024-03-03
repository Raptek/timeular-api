<?php

declare(strict_types=1);

namespace Timeular;

use Psr\Http\Message\ResponseInterface;
use Timeular\Http\Client;

class Timeular
{
    private Client $httpClient;

    public function __construct(
        ?Client $httpClient = null,
    ) {
        $this->httpClient = $httpClient ?? new Client('https://api.timeular.com/api/v3');
    }

    public function getToken(string $apiKey, string $apiSecret): string
    {
        $response = $this->httpClient->request(
            'POST',
            'developer/sign-in',
            [
                'apiKey' => $apiKey,
                'apiSecret' => $apiSecret,
            ]
        );

        return json_decode($response->getBody()->getContents())->token;
    }

    public function me(string $apiKey, string $apiSecret): ResponseInterface
    {
        $token = $this->getToken($apiKey, $apiSecret);

        return $this->httpClient->request(
            'GET',
            'me',
            [],
            [
                'Authorization' => sprintf('Bearer %s', $token),
            ]
        );
    }
}
