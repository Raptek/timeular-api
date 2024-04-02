<?php

declare(strict_types=1);

namespace Timeular\Api\TimeTracking;

use Timeular\Http\HttpClient;
use Timeular\Model\TimeTracking\Mention;
use Timeular\Model\TimeTracking\Tag;

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
}
