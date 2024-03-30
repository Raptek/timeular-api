#!/usr/bin/env php
<?php

require __DIR__.'/bootstrap.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Style\SymfonyStyle;
use Timeular\Model\TimeTracking\Device;
use Timeular\Timeular;

(new SingleCommandApplication())
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($container) {
        $style = new SymfonyStyle($input, $output);

        $timeular = $container->get(Timeular::class);

        $user = $timeular->me();

        $style->section('User');

        $style->horizontalTable(
            ['User ID', 'Name', 'Email', 'Default Space ID'],
            [
                $user->toArray()
            ]
        );

        $style->section('Devices');

        $devices = $timeular->devices();

        $style->table(
            ['Serial', 'Name', 'Active', 'Disabled'],
            array_map(static fn (Device $device): array => $device->toArray(), $devices),
        );
    })
    ->run();
