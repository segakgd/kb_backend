<?php

namespace App\Message;

final readonly class TelegramMessage
{
    public function __construct(
        private int $visitorEventId,
    ) {}

    public function getVisitorEventId(): int
    {
        return $this->visitorEventId;
    }
}
