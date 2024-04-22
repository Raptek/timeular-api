<?php

declare(strict_types=1);

namespace Timeular\UserProfile\Model;

enum Role: string
{
    case Member = 'Member';
    case Admin = 'Admin';
}
