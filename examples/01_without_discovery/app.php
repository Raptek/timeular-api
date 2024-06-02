#!/usr/bin/env php
<?php

require __DIR__ . '/bootstrap.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Timeular\Http\Factory\HttpClientFactory;
use Timeular\Http\Factory\RequestFactoryFactory;
use Timeular\Timeular;

$requestFactory = (new RequestFactoryFactory(new HttpFactory(), new HttpFactory()));

$httpClient = (new HttpClientFactory(
    getenv('API_KEY'),
    getenv('API_SECRET'),
    new Client(),
    $requestFactory,
))->create();

$timeular = new Timeular($httpClient);

$user = $timeular->me();

var_dump($user);
