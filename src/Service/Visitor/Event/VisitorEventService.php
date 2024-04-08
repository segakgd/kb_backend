<?php

namespace App\Service\Visitor\Event;

use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Enum\VisitorEventStatusEnum;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Visitor\Scenario\ScenarioService;
use App\Service\Visitor\Session\VisitorSessionService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class VisitorEventService
{
    public function __construct(
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly VisitorSessionService $visitorSessionService,
        private readonly ScenarioService $scenarioService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createVisitorEventForSession(
        VisitorSession $visitorSession,
        string $type,
        string $content,
    ): void {
        $cache = $visitorSession->getCache();

        $visitorEvent = null;
        $eventUUID = $cache->getEventUUID();

        if ($eventUUID) {
            $visitorEvent = $this->visitorEventRepository->getVisitorEventIfExistByScenarioUUID($eventUUID);
        }

        if (!$visitorEvent) {
            $visitorEvent = $this->createVisitorEventByScenario($visitorSession, $type, $content);
        }

        $cache->setEventUUID($visitorEvent->getScenarioUUID());
        $cache->setContent($content);

        if ($visitorEvent->getStatus() === VisitorEventStatusEnum::Waiting) {
            $visitorSession->setCache($cache); // todo зачем?
            $visitorEvent->setStatus(VisitorEventStatusEnum::New);

            $this->entityManager->persist($visitorSession);
            $this->entityManager->persist($visitorEvent);

            $this->entityManager->flush();

            return;
        }

        $visitorSession->setCache($cache);

        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        $this->rewriteChatEventByScenario($visitorEvent, $visitorSession, $type, $content);
    }

    /**
     * todo по сути, мы тут идём из main-a
     *
     * @throws Exception
     */
    private function createVisitorEventByScenario(
        VisitorSession $visitorSession,
        string $type,
        string $content
    ): VisitorEvent {
        $scenario = $this->scenarioService->findScenarioByNameAndType($type, $content);

        $visitorEvent = $this->createEvent($scenario, $type);
        $visitorEventId = $visitorEvent->getId();

        $visitorSession->setVisitorEvent($visitorEventId);

        $this->visitorSessionRepository->save($visitorSession);

        return $visitorEvent;
    }

    private function createEvent(Scenario $scenario, string $type): VisitorEvent
    {
        $visitorEvent = (new VisitorEvent())
            ->setType($type)
            ->setScenarioUUID($scenario->getUUID())
            ->setProjectId($scenario->getProjectId());

        $this->visitorEventRepository->saveAndFlush($visitorEvent);

        return $visitorEvent;
    }

    /**
     * @throws Exception
     */
    private function rewriteChatEventByScenario(
        VisitorEvent $visitorEvent,
        VisitorSession $visitorSession,
        string $type,
        string $content,
    ): void {
        $scenario = $this->scenarioService->findScenarioByNameAndType($type, $content);

        // один и тот же сценарий, нет смысла перезатирать
        if ($visitorEvent->getScenarioUUID() === $scenario->getUUID()) {
            return;
        }

        $oldEventId = $visitorSession->getVisitorEvent();
        $visitorEvent = $this->createEvent($scenario, $type);

        $this->visitorSessionService->rewriteVisitorEvent($visitorSession, $visitorEvent->getId());
        $this->visitorEventRepository->removeById($oldEventId);
    }
}
