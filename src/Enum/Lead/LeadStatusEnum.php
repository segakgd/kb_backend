<?php

declare(strict_types=1);

namespace App\Enum\Lead;

enum LeadStatusEnum: string
{
    case LEAD_STATUS_NEW = 'new';

    case LEAD_STATUS_PROCESS = 'process';

    case LEAD_STATUS_SUSPENDED = 'suspended';

    case LEAD_STATUS_REJECTED = 'rejected';

    case LEAD_STATUS_SUCCESSFUL = 'successful';
}
