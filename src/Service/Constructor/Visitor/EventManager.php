<?php

namespace App\Service\Constructor\Visitor;

use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Enum\VisitorEventStatusEnum;
use App\Helper\CacheHelper;
use App\Repository\Visitor\VisitorEventRepository;
use App\Service\Constructor\Visitor\Event\EventService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

readonly class EventManager
{
    public function __construct(
        private VisitorEventRepository $visitorEventRepository,
        private ScenarioManager $scenarioService,
        private EntityManagerInterface $entityManager,
        private EventService $eventService,
    ) {}

    /**
     * @throws Exception
     */
    public function createVisitorEventForSession(
        VisitorSession $visitorSession,
        string $type,
        string $content,
    ): VisitorEvent {
        $cache = $visitorSession->getCache();

        $visitorEvent = $this->visitorEventRepository->getVisitorEventIfExist($visitorSession);

        if ($visitorEvent?->getStatus() === VisitorEventStatusEnum::New) {
            $visitorEvent->setStatus(VisitorEventStatusEnum::Done);
            $this->visitorEventRepository->saveAndFlush($visitorEvent);

            $visitorEvent = null;
        }

        if ($visitorEvent?->getStatus() === VisitorEventStatusEnum::Done) {
            $visitorEvent = null;
        }

        if (null === $visitorEvent) {
            $scenario = $this->scenarioService->getScenarioByNameAndType($type, $content);
            $visitorEvent = $this->eventService->createEvent($visitorSession, $scenario, $type);
            $cache->setEvent(CacheHelper::createCacheEventDto());
        }

        $cache->setContent($content);

        $visitorSession->setCache($cache);

        if ($visitorEvent->getStatus() === VisitorEventStatusEnum::Waiting) {
            $visitorEvent->setStatus(VisitorEventStatusEnum::Repeat);

            $this->entityManager->persist($visitorEvent);
        }

        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        $this->entityManager->refresh($visitorEvent);

        return $visitorEvent;
    }
}
