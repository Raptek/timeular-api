<?php

declare(strict_types=1);

namespace Tests\Builders\Timeular\Http;

use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Tests\Builders\Timeular\BuilderInterface;
use Tests\Builders\Timeular\Http\Serializer\SerializerBuilder;
use Timeular\Http\HttpClient;
use Timeular\Http\HttpClientInterface;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\ResponseHandler;
use Timeular\Http\ResponseHandlerInterface;

class HttpClientBuilder implements BuilderInterface
{
    private array $dependencies = [];

    public function withApiKey(string $apiKey): self
    {
        $this->dependencies['api-key'] = $apiKey;

        return $this;
    }

    public function withApiSecret(string $apiSecret): self
    {
        $this->dependencies['api-secret'] = $apiSecret;

        return $this;
    }

    public function withPsrClient(ClientInterface $client): self
    {
        $this->dependencies['psr-client'] = $client;

        return $this;
    }

    public function withResponseHandler(ResponseHandlerInterface $responseHandler): self
    {
        $this->dependencies['response-handler'] = $responseHandler;

        return $this;
    }

    public function withRequestFactory(RequestFactoryInterface $requestFactory): self
    {
        $this->dependencies['request-factory'] = $requestFactory;

        return $this;
    }

    private function defaults(): void
    {
        $this->dependencies['api-key'] ??= getenv('API_KEY');
        $this->dependencies['api-secret'] ??= getenv('API_SECRET');
        $this->dependencies['psr-client'] ??= Psr18ClientDiscovery::find();
        $this->dependencies['response-handler'] ??= new ResponseHandler(new MediaTypeResolver(), (new SerializerBuilder())->build());
        $this->dependencies['request-factory'] ??= (new RequestFactoryBuilder())->build();
    }

    public function build(): HttpClientInterface
    {
        $this->defaults();

        return new HttpClient(
            $this->dependencies['api-key'],
            $this->dependencies['api-secret'],
            $this->dependencies['psr-client'],
            $this->dependencies['response-handler'],
            $this->dependencies['request-factory'],
        );
    }
}
