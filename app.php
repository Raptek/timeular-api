<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;
use Timeular\Timeular;

(new SingleCommandApplication())
    ->addArgument('key')
    ->addArgument('secret')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $timeular = new Timeular(
            $input->getArgument('key'),
            $input->getArgument('secret'),
            new Psr16Cache(new FilesystemAdapter(directory: '.cache')),
        );

        $data = $timeular->me();

        $output->writeln(sprintf('Name: %s', $data['name']));
        $output->writeln(sprintf('Email: %s', $data['email']));
    })
    ->run();
