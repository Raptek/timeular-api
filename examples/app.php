#!/usr/bin/env php
<?php

require __DIR__ . '/bootstrap.php';

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Timeular\Http\Factory\HttpClientFactory;
use Timeular\Http\Factory\RequestFactoryFactory;
use Timeular\Timeular;

$psr17RequestFactory = Psr17FactoryDiscovery::findRequestFactory();
$psr17StreamFactory = Psr17FactoryDiscovery::findStreamFactory();
$psr18Client = Psr18ClientDiscovery::find();

$httpClient = (new HttpClientFactory(
    getenv('API_KEY'),
    getenv('API_SECRET'),
    $psr18Client,
    new RequestFactoryFactory($psr17RequestFactory, $psr17StreamFactory),
))->create();

$timeular = new Timeular($httpClient);

$user = $timeular->me();

var_dump($user);
