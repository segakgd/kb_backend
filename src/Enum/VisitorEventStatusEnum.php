<?php

namespace App\Enum;

enum VisitorEventStatusEnum: string
{
    case Done = 'done';

    case InProcess = 'in_process';

    case New = 'new';

    case Repeat = 'repeat';

    case Waiting = 'waiting';

    case Failed = 'failed';
}
