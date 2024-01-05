<?php

namespace App\Service\System\Handler;

use App\Entity\Visitor\VisitorEvent;
use App\Service\System\Handler\Items\CommandHandler;
use App\Service\System\Handler\Items\MessageHandler;
use Exception;

class ActionHandler
{
    public function __construct(
        private readonly CommandHandler $commandHandler,
        private readonly MessageHandler $messageHandler,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(VisitorEvent $visitorEvent): bool
    {
        // todo не увеорен что это хорошее решение...

        return match ($visitorEvent->getType()) {
            'command' => $this->commandHandler->handle($visitorEvent),
            'message' => $this->messageHandler->handle($visitorEvent),
            default => false
        };
    }
}
