<?php

namespace App\Service\Visitor\Session;

use App\Dto\SessionCache\SessionCacheCartDto;
use App\Dto\SessionCache\SessionCacheDto;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorSessionRepository;
use DateTimeImmutable;
use Symfony\Component\Serializer\SerializerInterface;

class VisitorSessionService implements VisitorSessionServiceInterface
{
    public function __construct(
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly SerializerInterface $serializer,
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
        $cacheDto = (new SessionCacheDto())
            ->setCart(
                (new SessionCacheCartDto())
            )
        ;

        $cache = $this->serializer->normalize($cacheDto);

        $visitorSession = (new VisitorSession())
            ->setName($visitorName)
            ->setChannel($chanel)
            ->setChannelId($chatId)
            ->setProjectId($projectId)
            ->setCache($cache)
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
