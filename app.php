<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Timeular\Api\AuthApi;
use Timeular\Api\TimeularApi;
use Timeular\Http\Client;
use Timeular\Timeular;

(new SingleCommandApplication())
    ->addArgument('key')
    ->addArgument('secret')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $httpClient = new Client('https://api.timeular.com/api/v3');
        $auth = new AuthApi(
            $input->getArgument('key'),
            $input->getArgument('secret'),
            $httpClient,
        );
        $api = new TimeularApi(
            $httpClient,
            $auth,
        );

        $timeular = new Timeular($api);

        $data = $timeular->me();

        $output->writeln(sprintf('Name: %s', $data['name']));
        $output->writeln(sprintf('Email: %s', $data['email']));
    })
    ->run();
