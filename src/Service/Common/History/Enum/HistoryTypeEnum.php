<?php

namespace App\Service\Common\History\Enum;

enum HistoryTypeEnum: string
{
    case Incoming = 'incoming';
    case Outgoing = 'outgoing';
}
