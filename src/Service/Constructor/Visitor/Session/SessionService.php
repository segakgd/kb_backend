<?php

namespace App\Service\Constructor\Visitor\Session;

use App\Entity\SessionCache;
use App\Entity\User\Bot;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorSessionRepository;
use DateTimeImmutable;

readonly class SessionService
{
    public function __construct(
        private VisitorSessionRepository $visitorSessionRepository,
    ) {}

    public function findByChannel(int $chatId, int $botId, string $channel): ?VisitorSession
    {
        return $this->visitorSessionRepository->findOneBy(
            [
                'chatId'  => $chatId,
                'botId'   => $botId,
                'channel' => $channel,
            ]
        );
    }

    public function findByBot(Bot $bot): array
    {
        return $this->visitorSessionRepository->findBy(
            [
                'botId' => $bot->getId(),
            ]
        );
    }

    public function createSession(
        string $visitorName,
        int $chatId,
        Bot $bot,
        string $chanel,
        int $projectId,
    ): VisitorSession {
        $visitorSession = (new VisitorSession())
            ->setName($visitorName)
            ->setChannel($chanel)
            ->setChatId($chatId)
            ->setBot($bot)
            ->setProjectId($projectId)
            ->setCache(new SessionCache())
            ->setCreatedAt(new DateTimeImmutable());

        $this->visitorSessionRepository->saveAndFlush($visitorSession);

        return $visitorSession;
    }
}
