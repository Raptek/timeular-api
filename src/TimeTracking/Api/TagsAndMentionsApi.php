<?php

declare(strict_types=1);

namespace Timeular\TimeTracking\Api;

use Timeular\Http\HttpClient;
use Timeular\TimeTracking\Model\Mention;
use Timeular\TimeTracking\Model\Tag;

class TagsAndMentionsApi
{
    public function __construct(
        private HttpClient $httpClient,
    ) {
    }

    /**
     * @see https://developers.timeular.com/#03a2a812-cb0a-45e6-8fc6-74a0ff439909
     */
    public function tagsAndMentions(): array
    {
        $response = $this->httpClient->request(
            'GET',
            'tags-and-mentions',
        );

        return [
            'tags' => array_map(static fn (array $tagData): Tag => Tag::fromArray($tagData), $response['tags']),
            'mentions' => array_map(static fn (array $mentionData): Mention => Mention::fromArray($mentionData), $response['mentions']),
        ];
    }

    /**
     * @see https://developers.timeular.com/#d62392ca-2eb2-40c9-8d14-834ba581122e
     */
    public function createTag(
        string $key,
        string $label,
        string $scope,
        string $spaceId,
    ): Tag {
        $response = $this->httpClient->request(
            'POST',
            'tags',
            [
                'key' => $key,
                'label' => $label,
                'scope' => $scope,
                'spaceId' => $spaceId,
            ]
        );

        return Tag::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#34edd1e9-c5fd-47f3-83a6-bc16e6409d11
     */
    public function updateTag(
        string $id,
        string $label,
    ): Tag {
        $response = $this->httpClient->request(
            'PATCH',
            sprintf('tags/%s', $id),
            [
                'label' => $label,
            ]
        );

        return Tag::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#c930c6f5-e825-413e-b430-434a05e96e6c
     */
    public function deleteTag(
        string $id,
    ): array {
        $response = $this->httpClient->request(
            'PATCH',
            sprintf('tags/%s', $id),
        );

        return $response;
    }

    /**
     * @see https://developers.timeular.com/#b0de30da-39f4-4d21-b5d5-09e79940c820
     */
    public function createMention(
        string $key,
        string $label,
        string $scope,
        string $spaceId,
    ): Mention {
        $response = $this->httpClient->request(
            'POST',
            'mentions',
            [
                'key' => $key,
                'label' => $label,
                'scope' => $scope,
                'spaceId' => $spaceId,
            ]
        );

        return Mention::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#b00ccf63-701c-471f-abd1-31735f6224d3
     */
    public function updateMention(
        string $id,
        string $label,
    ): Mention {
        $response = $this->httpClient->request(
            'PATCH',
            sprintf('mentions/%s', $id),
            [
                'label' => $label,
            ]
        );

        return Mention::fromArray($response);
    }

    /**
     * @see https://developers.timeular.com/#a7e6b2fa-d879-4368-a4f1-eea14808eef8
     */
    public function deleteMention(
        string $id,
    ): array {
        $response = $this->httpClient->request(
            'PATCH',
            sprintf('mentions/%s', $id),
        );

        return $response;
    }
}
