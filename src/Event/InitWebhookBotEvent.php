<?php

namespace App\Event;

use App\Entity\User\Bot;

readonly class InitWebhookBotEvent
{
    public function __construct(
        private Bot $bot
    ) {}

    public function getBot(): Bot
    {
        return $this->bot;
    }
}
