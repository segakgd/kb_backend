<?php

namespace App\Service\Constructor\Event;

use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\Event;
use App\Entity\Visitor\Session;
use App\Repository\Visitor\VisitorEventRepository;

readonly class EventService
{
    public function __construct(
        private VisitorEventRepository $visitorEventRepository,
    ) {}

    public function create(Session $visitorSession, Scenario $scenario, string $type): Event
    {
        $visitorEvent = (new Event())
            ->setType($type)
            ->setScenarioUUID($scenario->getUUID())
            ->setSessionId($visitorSession->getId())
            ->setProjectId($scenario->getProjectId());

        $this->visitorEventRepository->saveAndFlush($visitorEvent);

        return $visitorEvent;
    }
}
