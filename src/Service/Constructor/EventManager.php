<?php

namespace App\Service\Constructor;

use App\Entity\SessionCache;
use App\Entity\Visitor\Event;
use App\Entity\Visitor\Session;
use App\Enum\VisitorEventStatusEnum;
use App\Helper\CacheHelper;
use App\Repository\Visitor\VisitorEventRepository;
use App\Service\Constructor\Event\EventService;
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
        Session $session,
        string $type,
        string $content,
    ): Event {
        $sessionCache = $session->getCache();

        if (is_null($sessionCache)) {
            $sessionCache = new SessionCache();
        }

        $event = $this->visitorEventRepository->getVisitorEventIfExist($session);

        if ($event?->getStatus() === VisitorEventStatusEnum::New) {
            $event->setStatus(VisitorEventStatusEnum::Done);
            $this->visitorEventRepository->saveAndFlush($event);

            $event = null;
        }

        if ($event?->getStatus() === VisitorEventStatusEnum::Done) {
            $event = null;
        }

        if (is_null($event)) {
            $scenario = $this->scenarioService->getScenarioByNameAndType($type, $content);
            $event = $this->eventService->create($session, $scenario, $type);

            $cacheEventDto = CacheHelper::createCacheEventDto();

            $sessionCache->setEvent($cacheEventDto);
        }

        $sessionCache->setContent($content);

        $session->setCache($sessionCache);

        if ($event->getStatus() === VisitorEventStatusEnum::Waiting) {
            $event->setStatus(VisitorEventStatusEnum::Repeat);

            $this->entityManager->persist($event);
        }

        $this->entityManager->persist($session);
        $this->entityManager->flush();

        $this->entityManager->refresh($event);

        return $event;
    }
}
