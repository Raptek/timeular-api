<?php

declare(strict_types=1);

namespace Tests\Unit\Timeular;

use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Timeular\Http\Factory\HttpClientFactoryInterface;
use Timeular\Http\Factory\ResponseHandlerFactory;
use Timeular\Http\HttpClient;
use Timeular\Http\HttpClientInterface;

class HttpClientFactory implements HttpClientFactoryInterface
{
    public function __construct(private PsrClientInterface $httpClient) {}

    public function create(): HttpClientInterface
    {
        return new HttpClient(
            'test',
            'test',
            $this->httpClient,
            (new ResponseHandlerFactory())->create(),
            (new RequestFactoryFactory())->create(),
        );
    }
}
