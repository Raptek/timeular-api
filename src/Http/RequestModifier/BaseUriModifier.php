<?php

declare(strict_types=1);

namespace Timeular\Http\RequestModifier;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriFactoryInterface;

class BaseUriModifier implements RequestModifierInterface
{
    public function __construct(
        private string $baseUri,
        private UriFactoryInterface $uriFactory,
    ) {
    }
    public function enrich(RequestInterface $request): RequestInterface
    {
        $uri = $request->getUri();

        if ($uri->getHost() !== '') {
            return $request;
        }

        $uri = $this->uriFactory->createUri(sprintf('%s/%s', $this->baseUri, $request->getUri()));

        return $request->withUri($uri);
    }
}
