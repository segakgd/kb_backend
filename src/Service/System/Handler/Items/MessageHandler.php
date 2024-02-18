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
        $scenario = $this->scenarioRepository->findOneBy(
            [
                'UUID' => $visitorEvent->getScenarioUUID(),
            ]
        );

//        dd($scenario, $visitorEvent);

        if (!$scenario) {
            throw new Exception('Не существует сценария');
        }

        $visitorSession = $this->visitorSessionRepository->findByEventId($visitorEvent->getId());
        $scenarioSteps = $scenario->getSteps();

        foreach ($scenarioSteps as $scenarioStep) {
            $messageDto = (new MessageDto())
                ->setChatId($visitorSession->getChatId())
            ;

            if ($scenarioStep['message']) {
                $messageDto->setText($scenarioStep['message']);
            }

            if (!empty($scenarioStep['keyboard'])) {
                $replyMarkups = $this->keyboard($scenarioStep);

                if (!empty($replyMarkups)) {
                    $messageDto->setReplyMarkup($replyMarkups);
                }
            }

//            if (!empty($scenarioStep['chain'])) {
//                foreach ($scenarioStep['chain'] as $chainItem) {
//                    dd($chainItem);
//                }
//
//                dd($scenarioStep['chain']);
//            }
//
//            if (!empty($scenarioStep['attached'])) {
//                dd($scenarioStep['attached']);
//            }

            $this->telegramService->sendMessage(
                $messageDto,
                '6722125407:AAEDDnc7qpbaZpZg-wpfXQ5h7Yp5mhJND0U'
            ); // todo токен брать из настрек
        }

        return true;
    }

    private function keyboard(array $scenarioStep): array
    {
        $replyMarkups = [];

        foreach ($scenarioStep['keyboard']['replyMarkup'] as $key => $replyMarkup) {
            foreach ($replyMarkup as $keyItem => $replyMarkupItem) {
                $replyMarkups[$key][$keyItem]['text'] = $replyMarkupItem['text'];
            }
        }

        return $replyMarkups;
    }
}
