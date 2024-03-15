#!/usr/bin/env php
<?php

require __DIR__.'/bootstrap.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Timeular\Api\AuthApi;
use Timeular\Api\TimeularApi;
use Timeular\Http\ApiClient;
use Timeular\Http\Client;
use Timeular\Timeular;

(new SingleCommandApplication())
    ->addArgument('key')
    ->addArgument('secret')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($container) {
//        $apiClient = new ApiClient();
//        $authApi = new AuthApi(
//            $input->getArgument('key'),
//            $input->getArgument('secret'),
//            $apiClient,
//        );
//        $httpClient = Psr18ClientDiscovery::find();
//        $middlewareClient = new MiddlewareAwareClient(
//            new HandlerStack(new RequestMiddleware()),
//            new BaseUrlMiddleware('https://api.timeular.com/api/v3'),
//            new AuthMiddleware($authApi),
//        );

        $timeular = $container->get(Timeular::class);

        $data = $timeular->me();

        $output->writeln(sprintf('Name: %s', $data['name']));
        $output->writeln(sprintf('Email: %s', $data['email']));
    })
    ->run();
