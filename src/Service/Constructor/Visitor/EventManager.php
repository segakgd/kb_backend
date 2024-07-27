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
        $cache = $visitorSession->getCacheDto();

        $event = $this->visitorEventRepository->getVisitorEventIfExist($visitorSession);

        if ($event?->getStatus() === VisitorEventStatusEnum::New) {
            $event->setStatus(VisitorEventStatusEnum::Done);
            $this->visitorEventRepository->saveAndFlush($event);

            $event = null;
        }

        if ($event?->getStatus() === VisitorEventStatusEnum::Done) {
            $event = null;
        }

        if (null === $event) {
            $scenario = $this->scenarioService->getScenarioByNameAndType($type, $content);
            $event = $this->eventService->create($visitorSession, $scenario, $type);
            $cache->setEvent(CacheHelper::createCacheEventDto());
        }

        $cache->setContent($content);

        $visitorSession->setCacheDto($cache);

        if ($event->getStatus() === VisitorEventStatusEnum::Waiting) {
            $event->setStatus(VisitorEventStatusEnum::Repeat);

            $this->entityManager->persist($event);
        }

        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        $this->entityManager->refresh($event);

        return $event;
    }
}
