<?php

namespace App\Service\Visitor\Session;

use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorSessionRepository;

class VisitorSessionService
{
    public function __construct(
        private VisitorSessionRepository $chatSessionRepository,
    ) {
    }

    public function getOrCreateChatSession(int $chatId, string $channel): ?VisitorSession
    {
        $chatSession = $this->chatSessionRepository->getSessionByChatIdAndChannel($chatId, $channel);

        if (!$chatSession){
            $chatSession = $this->createChatService($chatId, $channel);
        }

        return $chatSession;
    }

    private function createChatService(int $chatId, string $channel): VisitorSession
    {
        $chatSession = (new VisitorSession())
            ->setChatId($chatId)
            ->setChannel($channel)
        ;

        $this->chatSessionRepository->save($chatSession);

        return $chatSession;
    }
}
