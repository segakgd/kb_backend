<?php

namespace App\Service\Constructor\Visitor\Event;

use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Enum\VisitorEventStatusEnum;
use App\Helper\CacheHelper;
use App\Repository\Visitor\VisitorEventRepository;
use App\Service\Constructor\Visitor\Scenario\ScenarioService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

readonly class VisitorEventService
{
    public function __construct(
        private VisitorEventRepository $visitorEventRepository,
        private ScenarioService $scenarioService,
        private EntityManagerInterface $entityManager,
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
            $scenario = $this->scenarioService->findScenarioByNameAndType($type, $content);
            $visitorEvent = $this->createEvent($visitorSession, $scenario, $type);
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

    private function createEvent(VisitorSession $visitorSession, Scenario $scenario, string $type): VisitorEvent
    {
        $visitorEvent = (new VisitorEvent())
            ->setType($type)
            ->setScenarioUUID($scenario->getUUID())
            ->setSessionId($visitorSession->getId())
            ->setProjectId($scenario->getProjectId());

        $this->visitorEventRepository->saveAndFlush($visitorEvent);

        return $visitorEvent;
    }
}
