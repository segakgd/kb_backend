<?php

namespace App\Service\System\Handler\Items;

use App\Entity\Visitor\VisitorEvent;
use App\Repository\Scenario\ScenarioRepository;
use App\Repository\User\BotRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\System\Handler\Items\Sub\ChainHandler;
use App\Service\System\Handler\Items\Sub\ScenarioHandler;
use App\Service\System\Handler\PreMessageDto;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MessageHandler
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly ScenarioRepository $scenarioRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly ChainHandler $chainHandler,
        private readonly ScenarioHandler $scenarioHandler,
        private readonly EntityManagerInterface $entityManager,
        private readonly BotRepository $botRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(VisitorEvent $visitorEvent): bool
    {
        $bot = $this->botRepository->find(10);
        $token = $bot->getToken();

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

        $status = $visitorSession->getCacheStatusEvent();
        $content = $visitorSession->getCacheContent();

        $preMessageDto = (new PreMessageDto())
            ->setMessage('Дефолтное сообщение...')
        ;

        if ($status === 'process') {
            $preMessageDto = $this->chainHandler->handle($preMessageDto, $cache, $content);

            $visitorSession->setCache($cache);
        } else {
            $preMessageDto = $this->scenarioHandler->handle($preMessageDto, $scenario);
        }

        $this->send($preMessageDto, $token, $visitorSession);

        $this->entityManager->persist($scenario);
        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        return true;
    }

    private function send($preMessageDto, $token, $visitorSession): void
    {
        if ($preMessageDto->getPhoto()) {
            $this->telegramService->sendPhoto(
                $preMessageDto,
                $token,
                $visitorSession->getChatId()
            );
        } else {
            $this->telegramService->sendMessage(
                $preMessageDto,
                $token,
                $visitorSession->getChatId()
            );
        }
    }
}
