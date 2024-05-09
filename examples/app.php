#!/usr/bin/env php
<?php

require __DIR__.'/bootstrap.php';
require __DIR__.'/container.php';

use Timeular\Timeular;

$timeular = $container->get(Timeular::class);

$user = $timeular->me();

var_dump($user);
