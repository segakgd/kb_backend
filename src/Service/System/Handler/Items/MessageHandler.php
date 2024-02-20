<?php

namespace App\Service\System\Handler\Items;

use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Entity\Visitor\VisitorEvent;
use App\Repository\Scenario\ScenarioRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\System\Handler\ChainHandler;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MessageHandler
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly ScenarioRepository $scenarioRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly ChainHandler $chainHandler,
        private readonly EntityManagerInterface $entityManager,
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

        $cache = $visitorSession->getCache();

        $status = $cache['event']['status'];
        $content = $cache['content'];

        if ($status === 'process') {
            $messageDto = (new MessageDto())
                ->setChatId($visitorSession->getChatId());

            $messageDto->setText('Дефолтное сообщение...');

            foreach ($cache['event']['chains'] as $chain) {
                if ($chain['finished'] === false) {
                    $this->chainHandler->handleByType($chain['target'], $chain['action'], $messageDto, $content);

                    break;
                }
            }

            $this->telegramService->sendMessage(
                $messageDto,
                '6722125407:AAEDDnc7qpbaZpZg-wpfXQ5h7Yp5mhJND0U'
            );
        }


        dd('ddslslslsd');


        $scenarioSteps = $scenario->getSteps();

        foreach ($scenarioSteps as $stepKey => $scenarioStep) {
            $messageDto = (new MessageDto())
                ->setChatId($visitorSession->getChatId());

            if ($scenarioStep['message']) {
                $messageDto->setText($scenarioStep['message']);
            }

            if (!empty($scenarioStep['keyboard'])) {
                $replyMarkups = $this->keyboard($scenarioStep);

                if (!empty($replyMarkups)) {
                    $messageDto->setReplyMarkup($replyMarkups);
                }
            }

            if (!empty($scenarioStep['attached'])) {
                dd($scenarioStep['attached']);
            }

            if (!empty($scenarioStep['chain'])) {
                foreach ($scenarioStep['chain'] as $chainKey => $chainItem) {
                    if ($chainItem['finish'] === false) {
                        $this->chainHandler->handleByType($chainItem['target'], $chainItem['action'], $messageDto);

                        $scenarioSteps[$stepKey]['chain'][$chainKey]['finish'] = true;

                        break;
                    }
                }
            }

            $this->telegramService->sendMessage(
                $messageDto,
                '6722125407:AAEDDnc7qpbaZpZg-wpfXQ5h7Yp5mhJND0U'
            ); // todo токен брать из настрек
        }

        $scenario->setSteps($scenarioSteps); // todo не туда сохраняем... нужно сетить в сессию.. как-то -_-

        $this->entityManager->persist($scenario);
        $this->entityManager->flush();

        return true;
    }

    private function chain()
    {
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
