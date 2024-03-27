<?php

declare(strict_types=1);

namespace Timeular\Api;

use Timeular\Http\HttpClient;
use Timeular\Model\User;

class TimeularApi
{
    public function __construct(
        private HttpClient $httpClient,
    ) {
    }

    /**
     * @see https://developers.timeular.com/#bbf459e2-ff90-4aeb-b064-7febaa4eba70
     */
    public function me(): User
    {
        $response = $this->httpClient->request(
            'GET',
            'me',
        );

        return User::fromArray($response['data']);
    }
}
