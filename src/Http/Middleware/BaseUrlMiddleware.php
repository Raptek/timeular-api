<?php

declare(strict_types=1);

namespace Timeular\Http\Middleware;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;

class BaseUrlMiddleware implements MiddlewareInterface
{
    private UriFactoryInterface $uriFactory;

    public function __construct(
        private string $baseUri,
        ?UriFactoryInterface $uriFactory = null,
    ) {
        $this->uriFactory = $uriFactory ?? Psr17FactoryDiscovery::findUriFactory();
    }

    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();

        if ($uri->getHost() !== '') {
            return $handler->handle($request);
        }

        $request->withUri($this->uriFactory->createUri(sprintf('%s/%s', $this->baseUri, $request->getUri())));

        return $handler->handle($request);
    }
}
