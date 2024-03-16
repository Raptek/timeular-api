<?php

declare(strict_types=1);

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Timeular\Api\AuthApi;
use Timeular\Api\TimeularApi;
use Timeular\Http\ApiClient;
use Timeular\Http\Client;
use Timeular\Http\Middleware\AuthMiddleware;
use Timeular\Http\Middleware\BaseUrlMiddleware;
use Timeular\Http\Middleware\HandlerStack;
use Timeular\Http\Middleware\RequestHandlerStackInterface;
use Timeular\Http\Middleware\RequestMiddleware;
use Timeular\Http\MiddlewareAwareClient;
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

//    $services
//        ->set(ApiClient::class)
//        ->arg('$httpClient', new Reference(MiddlewareAwareClient::class))
//    ;

    $services
        ->set(AuthApi::class)
        ->arg('$apiKey', getenv('API_KEY'))
        ->arg('$apiSecret', getenv('API_SECRET'))
        ->public()
    ;

//    $services
//        ->set(BaseUrlMiddleware::class)
//        ->args(['%http.base_uri%']);
//    $services
//        ->set(AuthMiddleware::class);
//    $services
//        ->set(RequestMiddleware::class);
//    $services
//        ->set(HandlerStack::class)
//        ->args([RequestMiddleware::class]);
//    $services
//        ->alias(RequestHandlerStackInterface::class, HandlerStack::class);
//
//    $services
//        ->set(MiddlewareAwareClient::class)
//        ->args(
//            [
//                new Reference(RequestHandlerStackInterface::class),
//                new Reference(BaseUrlMiddleware::class),
//                new Reference(AuthMiddleware::class),
//            ]
//        );


    $services
        ->set(Client::class)
        ->args(['%http.base_uri%'])
        ;
    $services
        ->set(TimeularApi::class)
        ;
    $services
        ->set(Timeular::class)
        ->public();

};
