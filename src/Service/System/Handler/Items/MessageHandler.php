<?php

namespace App\Service\System\Handler\Items;

use App\Dto\Core\Telegram\Message\MessageDto;
use App\Entity\Visitor\VisitorEvent;
use App\Repository\Scenario\ScenarioRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Integration\Telegram\TelegramService;

class MessageHandler
{
    public function __construct(
        private TelegramService $telegramService,
        private ScenarioRepository $behaviorScenarioRepository,
        private VisitorSessionRepository $chatSessionRepository,
    ) {
    }

    public function handle(VisitorEvent $chatEvent): void
    {
        $behaviorScenarioId = $chatEvent->getBehaviorScenario();

        $behaviorScenario = $this->behaviorScenarioRepository->find($behaviorScenarioId);
        $behaviorScenarioContent = $behaviorScenario->getContent();

        $chatSession = $this->chatSessionRepository->findOneBy(
            [
                'chatEvent' => $chatEvent->getId()
            ]
        );

        $messageDto = (new MessageDto())
            ->setChatId($chatSession->getChatId())
            ->setText($behaviorScenarioContent['message'])
        ;

        if(!empty($behaviorScenarioContent['replyMarkup'])){
            $messageDto->setReplyMarkup($behaviorScenarioContent['replyMarkup']);
        }

        $this->telegramService->sendMessage($messageDto, '5109953245:AAE7TIhplLRxJdGmM27YSeSIdJdOh4ZXVVY');
    }
}