<?php

namespace App\Service\System\Handler\Items;

use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
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

        if (!$scenario) {
            throw new Exception('Не существует сценария');
        }

        $visitorSession = $this->visitorSessionRepository->findByEventId($visitorEvent->getId());

        $cache = $visitorSession->getCache();

        $status = $cache['event']['status'] ?? null;
        $content = $cache['content'];

        if ($status === 'process') {
            $messageDto = $this->chain($cache, $content, $visitorSession);
        } else {
            $messageDto = $this->scenario($scenario, $visitorSession);
        }

        $this->telegramService->sendMessage(
            $messageDto,
            '6722125407:AAEDDnc7qpbaZpZg-wpfXQ5h7Yp5mhJND0U'
        ); // todo токен брать из настрек

        $this->entityManager->persist($scenario);
        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        return true;
    }

    private function chain(array $cache, string $content, VisitorSession $visitorSession): MessageDto
    {
        $messageDto = (new MessageDto())
            ->setChatId($visitorSession->getChatId());

        $messageDto->setText('Дефолтное сообщение...');

        $chains = $cache['event']['chains'];

        foreach ($chains as $key => $chain) {
            if ($chain['finished'] === false) {
                $this->chainHandler->handleByType($chain['target'], $chain['action'], $messageDto, $content);

                $chains[$key]['finished'] = true;

                if (count($chains) === ($key + 1)) {
                    $cache['event']['status'] = 'finished';
                }

                break;
            }
        }

        $cache['event']['chains'] = $chains;

        $visitorSession->setCache($cache);

        return $messageDto;
    }

    private function scenario(Scenario $scenario, VisitorSession $visitorSession): MessageDto
    {
        $messageDto = (new MessageDto())
            ->setChatId($visitorSession->getChatId());

        $scenarioSteps = $scenario->getSteps();

        foreach ($scenarioSteps as $scenarioStep) {
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
                dd('сработали attached', $scenarioStep['attached']);
            }
        }

        return $messageDto;
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
