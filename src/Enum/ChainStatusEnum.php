<?php

namespace App\Enum;

enum ChainStatusEnum: string
{
    case Done = 'done';

    case New = 'new';

    case Await = 'await';

    case Failed = 'failed';
}
