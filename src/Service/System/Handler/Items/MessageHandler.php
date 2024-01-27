<?php

namespace App\Service\System\Handler\Items;

use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Entity\Visitor\VisitorEvent;
use App\Repository\Scenario\ScenarioRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Integration\Telegram\TelegramService;
use Exception;

class MessageHandler
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly ScenarioRepository $scenarioRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(VisitorEvent $visitorEvent): bool
    {
        $behaviorScenarioId = $visitorEvent->getBehaviorScenario();
        $behaviorScenario = $this->scenarioRepository->find($behaviorScenarioId);

        if (!$behaviorScenario){
            throw new Exception('Не существует сценария');
        }

        $behaviorScenarioContent = $behaviorScenario->getContent();
        $visitorSession = $this->visitorSessionRepository->findByEventId($visitorEvent->getId());

        $messageDto = (new MessageDto())
            ->setChatId($visitorSession->getChannelId())
            ->setText($behaviorScenarioContent['message'])
        ;

        if(!empty($behaviorScenarioContent['replyMarkup'])){
            $messageDto->setReplyMarkup($behaviorScenarioContent['replyMarkup']);
        }

        $this->telegramService->sendMessage($messageDto, '6722125407:AAEDDnc7qpbaZpZg-wpfXQ5h7Yp5mhJND0U'); // todo токен брать из настрек

        return true;
    }
}
