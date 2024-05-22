<?php

namespace App\Service\Visitor\Session;

use App\Entity\Visitor\VisitorSession;
use App\Helper\CommonHelper;
use App\Repository\Visitor\VisitorSessionRepository;
use DateTimeImmutable;

class VisitorSessionService
{
    public function __construct(
        private readonly VisitorSessionRepository $visitorSessionRepository,
    ) {
    }

    public function findAll(int $projectId): array
    {
        return $this->visitorSessionRepository->findBy(
            [
                'projectId' => $projectId
            ]
        );
    }

    public function findAllByBotId(int $botId): array
    {
        return $this->visitorSessionRepository->findBy(
            [
                'botId' => $botId
            ]
        );
    }

    public function identifyByChannel(int $chatId, int $botId, string $channel): ?VisitorSession
    {
        return $this->visitorSessionRepository->findOneBy(
            [
                'chatId' => $chatId,
                'botId' => $botId,
                'channel' => $channel,
            ]
        );
    }

    public function createVisitorSession(
        string $visitorName,
        int $chatId,
        int $botId,
        string $chanel,
        int $projectId,
    ): VisitorSession {
        $cacheDto = CommonHelper::createSessionCache();

        $visitorSession = (new VisitorSession())
            ->setName($visitorName)
            ->setChannel($chanel)
            ->setChatId($chatId)
            ->setBotId($botId)
            ->setProjectId($projectId)
            ->setCache($cacheDto)
            ->setCreatedAt(new DateTimeImmutable())
        ;

        $this->visitorSessionRepository->save($visitorSession);

        return $visitorSession;
    }

    public function rewriteVisitorEvent(VisitorSession $visitorSession, int $visitorEventId): void
    {
        $visitorSession->setVisitorEvent($visitorEventId);

        $this->visitorSessionRepository->save($visitorSession);
    }
}
