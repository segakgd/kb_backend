<?php

namespace App\Service\Visitor\Event;

use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Admin\Scenario\BehaviorScenarioService;
use App\Service\Visitor\Session\VisitorSessionServiceInterface;
use Exception;

class VisitorEventService
{
    public function __construct(
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly VisitorSessionServiceInterface $visitorSessionService,
        private readonly BehaviorScenarioService $behaviorScenarioService,
    ) {
    }

    public function findAllByProjectId(int $projectId): array
    {
        return $this->visitorEventRepository->findBy(
            [
                'projectId' => $projectId
            ]
        );
    }

    public function findOneById(int $id): ?VisitorEvent
    {
        return $this->visitorEventRepository->find($id);
    }

    public function findOneByStatus(string $status): ?VisitorEvent
    {
        return $this->visitorEventRepository->findOneBy(
            [
                'status' => $status,
            ]
        );
    }

    public function updateChatEventStatus(VisitorEvent $chatEvent, string $status): void
    {
        $chatEvent->setStatus($status);

        $this->visitorEventRepository->saveAndFlush($chatEvent);
    }

    /**
     * @throws Exception
     */
    public function createVisitorEventForSession(VisitorSession $visitorSession, string $type, string $content): void
    {
        $visitorEventId = $visitorSession->getVisitorEvent();

        $visitorEvent = $this->getVisitorEventIsExist($visitorEventId);

        if (!$visitorEvent) {
            $this->createVisitorEventByScenario($visitorSession, $type, $content);

            return;
        }

        $this->rewriteChatEventByScenario($visitorEvent, $visitorSession, $type, $content);
    }

    public function getVisitorEventIsExist(?int $visitorEventId): ?VisitorEvent
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
        $scenario = $this->behaviorScenarioService->getScenarioByNameAndType($type, $content);

        if (null === $scenario) {
            throw new Exception('Не существует ни одного сценария');
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

    private function rewriteChatEventByScenario(
        VisitorEvent $visitorEvent,
        VisitorSession $visitorSession,
        string $type,
        string $content,
    ): void {
        $scenario = $this->behaviorScenarioService->getScenarioByNameAndType($type, $content);

        if (!$scenario) { // todo что тут проиходит?
            $ownerBehaviorScenarioId = $visitorEvent->getBehaviorScenario();
            $scenario = $this->behaviorScenarioService->getScenarioByOwnerId($ownerBehaviorScenarioId);
        }

        if (!$scenario) { // todo как сюда дойти? оО
            $scenario = $this->behaviorScenarioService->generateDefaultScenario();
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
