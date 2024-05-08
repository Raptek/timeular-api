<?php

declare(strict_types=1);

namespace Timeular\Integrations\Api;

use Timeular\Http\HttpClient;

readonly class IntegrationsApi
{
    public function __construct(
        private HttpClient $httpClient,
    ) {
    }

    public function listEnabledIntegrations(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'integrations',
        );

        return $response['integrations'];
    }
}
