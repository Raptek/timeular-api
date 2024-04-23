<?php

declare(strict_types=1);

namespace Timeular\Http;

use Psr\Http\Message\MessageInterface;

interface MediaTypeResolverInterface
{
    public function getMediaTypeFromMessage(MessageInterface $message): string;
}
