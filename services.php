<?php

declare(strict_types=1);

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Timeular\Api\AuthApi;
use Timeular\Api\TimeTracking\DevicesApi;
use Timeular\Api\TimeularApi;
use Timeular\Api\UserProfile\SpaceApi;
use Timeular\Api\UserProfile\UserApi;
use Timeular\Http\HttpClient;
use Timeular\Http\RequestModifier\AuthModifier;
use Timeular\Http\RequestModifier\BaseUriModifier;
use Timeular\Http\RequestModifier\CompositeModifier;
use Timeular\Http\RequestModifier\RequestModifierInterface;
use Timeular\Serializer\JsonSerializer;
use Timeular\Serializer\SerializerInterface;
use Timeular\Timeular;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('http.base_uri', 'https://api.timeular.com/api/v3')
    ;

    $services = $container->services();

    $services
        ->defaults()
        ->autowire();

    $services
        ->set(ClientInterface::class)
        ->factory([Psr18ClientDiscovery::class, 'find']);
    $services
        ->set(RequestFactoryInterface::class)
        ->factory([Psr17FactoryDiscovery::class, 'findRequestFactory']);
    $services
        ->set(JsonSerializer::class);
    $services
        ->alias(SerializerInterface::class, JsonSerializer::class);

    $services
        ->set(HttpClient::class)
        ->arg('$baseUri', '%http.base_uri%')
        ->arg('$apiKey', getenv('API_KEY'))
        ->arg('$apiSecret', getenv('API_SECRET'))
    ;

    $services
        ->set(UserApi::class);
    $services
        ->set(SpaceApi::class);
    $services
        ->set(DevicesApi::class);
    $services
        ->set(Timeular::class)
        ->public();
};
