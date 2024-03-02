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

    public function getToken(string $apiKey, string $apiSecret): ResponseInterface
    {
        return $this->httpClient->post(
            'developer/sign-in',
            [
                'apiKey' => $apiKey,
                'apiSecret' => $apiSecret,
            ]
        );
    }
}
