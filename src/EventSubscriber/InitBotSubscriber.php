<?php

namespace App\EventSubscriber;

use App\Event\InitBotEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InitBotSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            InitBotEvent::class => 'initBot',
        ];
    }

    public function initBot(InitBotEvent $event): void
    {
    }
}
