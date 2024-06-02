#!/usr/bin/env php
<?php

require __DIR__ . '/bootstrap.php';

use Timeular\Http\Factory\DiscoverableHttpClientFactory;
use Timeular\Timeular;

$httpClient = (new DiscoverableHttpClientFactory(
    getenv('API_KEY'),
    getenv('API_SECRET'),
))->create();

$timeular = new Timeular($httpClient);

$user = $timeular->me();

var_dump($user);
