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

        $response = $timeular->getToken(
            $input->getArgument('key'),
            $input->getArgument('secret'),
        );

        $output->write(json_decode($response->getBody()->getContents())->token);
    })
    ->run();
