<?php

namespace App\Service\System\Handler\Items;

use App\Dto\Core\Telegram\Message\MessageDto;
use App\Entity\Visitor\VisitorEvent;
use App\Repository\Scenario\ScenarioRepository;
use App\Repository\Visitor\VisitorRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Integration\Telegram\TelegramService;
use Exception;

class MessageHandler
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly ScenarioRepository $behaviorScenarioRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly VisitorRepository $visitorRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(VisitorEvent $chatEvent): void
    {
        $behaviorScenarioId = $chatEvent->getBehaviorScenario();
        $behaviorScenario = $this->behaviorScenarioRepository->find($behaviorScenarioId);

        if (!$behaviorScenario){
            throw new Exception('Не существует сценария');
        }

        $behaviorScenarioContent = $behaviorScenario->getContent();

        $chatSession = $this->visitorSessionRepository->findOneBy(
            [
                'chatEvent' => $chatEvent->getId()
            ]
        );

        $visitor = $this->visitorRepository->find($chatSession->getVisitorId());

        $messageDto = (new MessageDto())
            ->setChatId($visitor->getChannelVisitorId())
            ->setText($behaviorScenarioContent['message'])
        ;

        if(!empty($behaviorScenarioContent['replyMarkup'])){
            $messageDto->setReplyMarkup($behaviorScenarioContent['replyMarkup']);
        }

        $this->telegramService->sendMessage($messageDto, '6722125407:AAEDDnc7qpbaZpZg-wpfXQ5h7Yp5mhJND0U');
    }
}
