<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

(new Dotenv())->load(__DIR__ . '/.env');
