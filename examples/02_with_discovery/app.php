#!/usr/bin/env php
<?php

require __DIR__ . '/bootstrap.php';

use Timeular\Http\Factory\DiscoverableHttpClientFactory;
use Timeular\Timeular;

$httpClient = (new DiscoverableHttpClientFactory(
    $_ENV['API_KEY'],
    $_ENV['API_SECRET'],
))->create();

$timeular = new Timeular($httpClient);

$user = $timeular->me();

var_dump($user);
