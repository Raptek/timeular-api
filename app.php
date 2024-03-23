#!/usr/bin/env php
<?php

require __DIR__.'/bootstrap.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Style\SymfonyStyle;
use Timeular\Timeular;

(new SingleCommandApplication())
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($container) {
        $style = new SymfonyStyle($input, $output);

        $timeular = $container->get(Timeular::class);

        $data = $timeular->me();

        $style->section('User');

        $style->horizontalTable(
            ['Name', 'Email'],
            [
                [$data['name'], $data['email']]
            ]
        );

        $style->section('Devices');

        $devices = $timeular->devicesList();

        $style->table(
            ['Serial', 'Name', 'Active', 'Disabled'],
            $devices,
        );
    })
    ->run();
