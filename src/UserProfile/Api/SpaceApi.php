<?php

declare(strict_types=1);

namespace Timeular\UserProfile\Api;

use Timeular\Http\HttpClientInterface;
use Timeular\UserProfile\Model\Space;

readonly class SpaceApi
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    /**
     * @see https://developers.timeular.com/#a5bba235-9229-48cb-a5f9-ee557a0bacf9
     */
    public function spacesWithMembers(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'space',
        );

        $spaces = [];

        foreach ($response['data'] as $spaceData) {
            $spaces[] = Space::fromArray($spaceData);
        }

        return $spaces;
    }
}
