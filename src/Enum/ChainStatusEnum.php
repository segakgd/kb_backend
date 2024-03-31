<?php

namespace App\Enum;

enum ChainStatusEnum: string
{
    case Done = 'done';

    case Await = 'await';

    case Failed = 'failed';
}
