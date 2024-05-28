<?php

declare(strict_types=1);

namespace Timeular\Http\Factory;

use Timeular\Http\Serializer\JsonEncoder;
use Timeular\Http\Serializer\PassthroughEncoder;
use Timeular\Http\Serializer\Serializer;
use Timeular\Http\Serializer\SerializerInterface;

readonly class SerializerFactory implements SerializerFactoryInterface
{
    public function __construct(private array $encoders = []) {}

    public function create(): SerializerInterface
    {
        return new Serializer(
            [
                'application/json' => new JsonEncoder(),
                'text/csv' => new PassthroughEncoder(),
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => new PassthroughEncoder(),
            ] + $this->encoders,
        );
    }
}
