<?php

namespace App\Event;

use App\Entity\User\Bot;

class InitBotEvent
{
    public function __construct(
        private readonly Bot $bot
    ) {
    }

    public function getBot(): Bot
    {
        return $this->bot;
    }
}
