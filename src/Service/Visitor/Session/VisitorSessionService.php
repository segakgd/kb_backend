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

    public function getOrCreateSession(Visitor $visitor): ?VisitorSession
    {
        $visitorSession = $this->visitorSessionRepository->findOneBy(
            [
                'visitorId' => $visitor->getId()
            ]
        );

        if (!$visitorSession){
            $visitorSession = $this->createChatService($visitor);
        }

        return $visitorSession;
    }

    private function createChatService(Visitor $visitor): VisitorSession
    {
        $chatSession = (new VisitorSession())
            ->setVisitorId($visitor->getId())
            ->setCreatedAt(new DateTimeImmutable())
        ;

        $this->visitorSessionRepository->save($chatSession);

        return $chatSession;
    }
}
