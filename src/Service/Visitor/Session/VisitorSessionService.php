<?php

namespace App\Service\Visitor\Session;

use App\Entity\Visitor\Visitor;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorSessionRepository;
use DateTimeImmutable;

class VisitorSessionService implements VisitorSessionServiceInterface
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

    public function identifyByChannel(int $channelId, string $channel): ?VisitorSession
    {
        return $this->visitorSessionRepository->findOneBy(
            [
                'channel' => $channel,
                'channelId' => $channelId,
            ]
        );
    }

    public function createVisitorSession(
        string $visitorName,
        int $chatId,
        string $chanel,
        int $projectId,
    ): VisitorSession {
        $visitorSession = (new VisitorSession())
            ->setName($visitorName)
            ->setChannel($chanel)
            ->setChannelId($chatId)
            ->setProjectId($projectId)
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
