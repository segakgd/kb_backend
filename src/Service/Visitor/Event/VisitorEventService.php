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
        int $botId,
    ): void {
        $visitorEventId = $visitorSession->getVisitorEvent();

        $visitorEvent = $this->getVisitorEventIsExist($visitorEventId);

        if (!$visitorEvent) {
            $this->createVisitorEventByScenario($visitorSession, $type, $content);

            return;
        }

        $this->rewriteChatEventByScenario($visitorEvent, $visitorSession, $type, $content, $botId);
    }

    private function getVisitorEventIsExist(?int $visitorEventId): ?VisitorEvent
    {
        if (!$visitorEventId) {
            return null;
        }

        return $this->visitorEventRepository->findOneBy(
            [
                'id' => $visitorEventId,
                'status' => 'new',
            ]
        );
    }

    /**
     * @throws Exception
     */
    private function createVisitorEventByScenario(VisitorSession $visitorSession, string $type, string $content): void
    {
        $scenario = $this->scenarioService->getScenarioByNameAndType($type, $content);

        if (null === $scenario) {
            $scenario = $this->scenarioService->getDefaultScenario();
        }

        $visitorEvent = $this->createChatEvent($scenario, $type);
        $visitorEventId = $visitorEvent->getId();

        $visitorSession->setVisitorEvent($visitorEventId);

        $this->visitorSessionRepository->save($visitorSession);
    }

    private function createChatEvent(Scenario $scenario, string $type): VisitorEvent
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
        int $botId,
    ): void {
        $scenario = $this->scenarioService->getScenarioByNameAndType($type, $content);

        if (!$scenario) { // todo что тут проиходит?
            $ownerBehaviorScenarioId = $visitorEvent->getBehaviorScenario();
            $scenario = $this->scenarioService->getScenarioByOwnerId($ownerBehaviorScenarioId);
        }

        if (!$scenario) { // todo как сюда дойти? оО
            $scenario = $this->scenarioService->generateDefaultScenario($visitorEvent->getProjectId(), $botId);
        }

        // один и тот же сценарий, нет смысла перезатирать
        if ($visitorEvent->getBehaviorScenario() === $scenario->getId()) {
            return;
        }

        $oldEventId = $visitorSession->getVisitorEvent();
        $visitorEvent = $this->createChatEvent($scenario, $type);

        $this->visitorSessionService->rewriteVisitorEvent($visitorSession, $visitorEvent->getId());
        $this->visitorEventRepository->removeById($oldEventId);
    }
}
