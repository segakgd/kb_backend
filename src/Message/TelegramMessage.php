<?php

namespace App\Message;

use App\Entity\Visitor\VisitorEvent;

final readonly class TelegramMessage
{
    public function __construct(
        private VisitorEvent $visitorEvent,
    ) {
    }

    public function getVisitorEvent(): VisitorEvent
    {
        return $this->visitorEvent;
    }
}
