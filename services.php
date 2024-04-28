<?php

declare(strict_types=1);

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface as PsrRequestFactoryInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Timeular\Http\HttpClient;
use Timeular\Http\MediaTypeResolver;
use Timeular\Http\MediaTypeResolverInterface;
use Timeular\Http\RequestFactory;
use Timeular\Http\RequestFactoryInterface;
use Timeular\Http\ResponseHandler;
use Timeular\Http\ResponseHandlerInterface;
use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\PassthroughEncoder;
use Timeular\Http\Serializer\Serializer;
use Timeular\Http\Serializer\SerializerInterface;
use Timeular\TimeTracking\Api\ActivitiesApi;
use Timeular\TimeTracking\Api\CurrentTrackingApi;
use Timeular\TimeTracking\Api\DevicesApi;
use Timeular\TimeTracking\Api\ReportsApi;
use Timeular\TimeTracking\Api\TagsAndMentionsApi;
use Timeular\TimeTracking\Api\TimeEntriesApi;
use Timeular\Timeular;
use Timeular\UserProfile\Api\SpaceApi;
use Timeular\UserProfile\Api\UserApi;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services
        ->defaults()
        ->autowire();

    $services
        ->set(ClientInterface::class)
        ->factory([Psr18ClientDiscovery::class, 'find']);
    $services
        ->set(PsrRequestFactoryInterface::class)
        ->factory([Psr17FactoryDiscovery::class, 'findRequestFactory']);

    $services
        ->set(JsonEncoder::class);
    $services
        ->set(PassthroughEncoder::class);
    $services
        ->set(Serializer::class)
        ->arg('$encoders', [
            'application/json' => new Reference(JsonEncoder::class),
            'text/csv' => new Reference(PassthroughEncoder::class),
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => new Reference(PassthroughEncoder::class),
        ]);
    $services
        ->alias(SerializerInterface::class, Serializer::class);
    $services
        ->set(MediaTypeResolver::class)
        ->alias(MediaTypeResolverInterface::class, MediaTypeResolver::class);
    $services
        ->set(RequestFactory::class)
        ->alias(RequestFactoryInterface::class, RequestFactory::class);
    $services
        ->set(ResponseHandler::class)
        ->alias(ResponseHandlerInterface::class, ResponseHandler::class);

    $services
        ->set(HttpClient::class)
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
        ->set(TagsAndMentionsApi::class);
    $services
        ->set(ActivitiesApi::class);
    $services
        ->set(CurrentTrackingApi::class);
    $services
        ->set(TimeEntriesApi::class);
    $services
        ->set(ReportsApi::class);
    $services
        ->set(Timeular::class)
        ->public();
};
