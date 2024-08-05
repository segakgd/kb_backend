<?php

namespace App\Entity\User\Enum;

enum ProjectStatusEnum: string
{
    case Active = 'active';
    case Frozen = 'frozen';
    case Blocked = 'blocked';
}
