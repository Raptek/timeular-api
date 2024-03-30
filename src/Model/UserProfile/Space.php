<?php

declare(strict_types=1);

namespace Timeular\Model\UserProfile;

class Space
{
    private function __construct(
        public string $id,
        public string $name,
        public bool $default,
        public array $members,
        public array $retiredMembers,
    ) {
    }
}
