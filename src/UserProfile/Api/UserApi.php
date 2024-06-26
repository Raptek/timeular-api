<?php

declare(strict_types=1);

namespace Timeular\UserProfile\Api;

use Timeular\Http\HttpClientInterface;
use Timeular\UserProfile\Model\Me;

readonly class UserApi
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    /**
     * @see https://developers.timeular.com/#bbf459e2-ff90-4aeb-b064-7febaa4eba70
     */
    public function me(): Me
    {
        $response = $this->httpClient->request(
            'GET',
            'me',
        );

        return Me::fromArray($response['data']);
    }
}
