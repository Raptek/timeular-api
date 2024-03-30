<?php

declare(strict_types=1);

namespace Timeular\Model\UserProfile;

enum Role: string
{
    case Member = 'Member';
    case Admin = 'Admin';
}
