<?php

declare(strict_types=1);

namespace Timeular\Model\UserProfile;

class RetiredUser
{
    private function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
