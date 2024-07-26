<?php

namespace App\Service\Constructor\Visitor\Event;

use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorEventRepository;

readonly class EventService
{
    public function __construct(
        private VisitorEventRepository $visitorEventRepository,
    ) {}

    public function create(VisitorSession $visitorSession, Scenario $scenario, string $type): VisitorEvent
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
