<?php

namespace App\Service\Constructor\Visitor\Session;

use App\Entity\SessionCache;
use App\Entity\User\Bot;
use App\Entity\Visitor\VisitorSession;
use App\Enum\Constructor\ChannelEnum;
use App\Repository\Visitor\VisitorSessionRepository;
use DateTimeImmutable;

readonly class SessionService
{
    public function __construct(
        private VisitorSessionRepository $visitorSessionRepository,
    ) {}

    public function findByMainParams(Bot $bot, int $chatId, ChannelEnum $channel): ?VisitorSession
    {
        return $this->visitorSessionRepository->findOneBy(
            [
                'chatId'  => $chatId,
                'bot'     => $bot,
                'channel' => $channel->value,
            ]
        );
    }

    public function findByBot(Bot $bot): array
    {
        return $this->visitorSessionRepository->findBy(
            [
                'bot' => $bot,
            ]
        );
    }

    public function createSession(
        Bot $bot,
        string $visitorName,
        int $chatId,
        ChannelEnum $chanel,
    ): VisitorSession {
        $visitorSession = (new VisitorSession())
            ->setName($visitorName)
            ->setChannel($chanel->value)
            ->setChatId($chatId)
            ->setBot($bot)
            ->setProjectId($bot->getProjectId())
            ->setCache(new SessionCache())
            ->setCreatedAt(new DateTimeImmutable());

        $this->visitorSessionRepository->saveAndFlush($visitorSession);

        return $visitorSession;
    }
}
