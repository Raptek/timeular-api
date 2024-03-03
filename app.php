<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Timeular\Timeular;

(new SingleCommandApplication())
    ->addArgument('key')
    ->addArgument('secret')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $timeular = new Timeular();

        $response = $timeular->me(
            $input->getArgument('key'),
            $input->getArgument('secret'),
        );

        $data = json_decode($response->getBody()->getContents())->data;

        $output->writeln(sprintf('Name: %s', $data->name));
        $output->writeln(sprintf('Email: %s', $data->email));
    })
    ->run();
