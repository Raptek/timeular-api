<?php

declare(strict_types=1);

namespace Timeular\Model\UserProfile;

class User
{
    private function __construct(
        public string $id,
        public string $name,
        public string $email,
        public Role $role,
    ) {
    }
}
