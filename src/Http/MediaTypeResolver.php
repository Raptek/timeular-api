<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Message\MessageInterface;

class MediaTypeResolver implements MediaTypeResolverInterface
{
    public function getMediaTypeFromMessage(MessageInterface $message): string
    {
        if (false === $message->hasHeader('Content-Type')) {
            throw new \InvalidArgumentException('Missing "Content-Type" header');
        }

        $contentTypes = $message->getHeader('Content-Type');

        if (1 !== \count($contentTypes)) {
            // Content-Type can have only one value!
        }

        $contentType = $contentTypes[0];

        [$mediaType, ] = explode(';', $contentType, 2);

        return $mediaType;
    }
}
