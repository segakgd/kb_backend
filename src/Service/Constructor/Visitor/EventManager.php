<?php

namespace App\Service\Constructor\Visitor;

use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Entity\SessionCache;
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
        VisitorSession $session,
        string $type,
        string $content,
    ): VisitorEvent {
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

        if (null === $event) {
            $scenario = $this->scenarioService->getScenarioByNameAndType($type, $content);
            $event = $this->eventService->create($session, $scenario, $type);
            $sessionCache->setEvent(CacheHelper::createCacheEventDto());
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
