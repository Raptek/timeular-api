<?php

declare(strict_types=1);

namespace Timeular\Webhooks\Exception;

class InvalidUrlException extends \Exception implements WebhooksException
{
    private function __construct(string $url)
    {
        parent::__construct(sprintf('URL "%s" is not valid.', $url));
    }

    public static function fromUrl(string $url): self
    {
        return new self($url);
    }
}
