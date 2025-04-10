<?php

namespace App\Entity\User\Enum;

enum ProjectStatusEnum: string
{
    case Active = 'active';
    case Enabled = 'enabled';
    case Blocked = 'blocked';
    case Trial = 'trial';
}
