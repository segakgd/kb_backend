<?php

namespace App\Enum\Constructor;

use App\Enum\EnumUtilsTrait;

enum ChannelEnum: string
{
    use EnumUtilsTrait;

    case Telegram = 'telegram';
}
