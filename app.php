#!/usr/bin/env php
<?php

require __DIR__.'/bootstrap.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Timeular\Timeular;

(new SingleCommandApplication())
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($container) {
        $timeular = $container->get(Timeular::class);

        $data = $timeular->me();

        $output->writeln(sprintf('Name: %s', $data['name']));
        $output->writeln(sprintf('Email: %s', $data['email']));
    })
    ->run();
