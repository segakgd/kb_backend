<?php

namespace App\Service\Visitor\Event;

use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Visitor\Scenario\ScenarioService;
use App\Service\Visitor\Session\VisitorSessionServiceInterface;
use Exception;

class VisitorEventService
{
    public function __construct(
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly VisitorSessionServiceInterface $visitorSessionService,
        private readonly ScenarioService $scenarioService,
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
        $visitorEventId = $visitorSession->getVisitorEvent();

        $visitorEvent = $this->visitorEventRepository->getVisitorEventIsExist($visitorEventId);

        if (!$visitorEvent) {
            $this->createVisitorEventByScenario($visitorSession, $type, $content);

            return;
        }

        $this->rewriteChatEventByScenario($visitorEvent, $visitorSession, $type, $content);
    }

    /**
     * @throws Exception
     */
    private function createVisitorEventByScenario(VisitorSession $visitorSession, string $type, string $content): void
    {
        $scenario = $this->scenarioService->getScenario($type, $content);

        $visitorEvent = $this->createEvent($scenario, $type);
        $visitorEventId = $visitorEvent->getId();

        $visitorSession->setVisitorEvent($visitorEventId);

        $this->visitorSessionRepository->save($visitorSession);
    }

    private function createEvent(Scenario $scenario, string $type): VisitorEvent
    {
        $visitorEvent = (new VisitorEvent())
            ->setType($type)
            ->setBehaviorScenario($scenario->getId())
            ->setActionAfter($scenario->getActionAfter() ?? null)
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
        $scenario = $this->scenarioService->getScenario($type, $content, $visitorEvent->getBehaviorScenario());

        // один и тот же сценарий, нет смысла перезатирать
        if ($visitorEvent->getBehaviorScenario() === $scenario->getId()) {
            return;
        }

        $oldEventId = $visitorSession->getVisitorEvent();
        $visitorEvent = $this->createEvent($scenario, $type);

        $this->visitorSessionService->rewriteVisitorEvent($visitorSession, $visitorEvent->getId());
        $this->visitorEventRepository->removeById($oldEventId);
    }
}
