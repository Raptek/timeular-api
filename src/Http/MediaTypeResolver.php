<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Message\MessageInterface;
use Timeular\Http\Exception\MissingContentTypeHeaderException;
use Timeular\Http\Exception\MultipleContentTypeValuesException;

/**
 * @internal
 */
class MediaTypeResolver implements MediaTypeResolverInterface
{
    public function getMediaTypeFromMessage(MessageInterface $message): string
    {
        if (false === $message->hasHeader('Content-Type')) {
            throw MissingContentTypeHeaderException::create();
        }

        $contentTypes = $message->getHeader('Content-Type');

        if (1 !== \count($contentTypes)) {
            throw MultipleContentTypeValuesException::create();
        }

        $contentType = array_pop($contentTypes);

        [$mediaType, ] = explode(';', $contentType, 2);

        return $mediaType;
    }
}
