<?php

namespace App\Service\Visitor\Event;

use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Visitor\Scenario\ScenarioService;
use App\Service\Visitor\Session\VisitorSessionServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class VisitorEventService
{
    public function __construct(
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly VisitorSessionServiceInterface $visitorSessionService,
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
        $visitorEventId = $visitorSession->getVisitorEvent();

        // в сессии ничего нету - создаём исходя из того что присалали на бота.
        // если это команда, то ищем из числа команд, ??? если нет то из доступных сообщений ???
        //
        // если мы знаем что присылали в прошлый раз, в сессии стоит prev, при этом, в нынешнее событие обработано
        // мы можем найти доступные варианты ответа на нужное нам сообщение

        $visitorEvent = $this->visitorEventRepository->getVisitorEventIfExist($visitorEventId);

        if (!$visitorEvent) {
            dd('Пытаемя создать новое событие');
            $this->createVisitorEventByScenario($visitorSession, $type, $content);

            return;
        }

        // todo обновляем кэш >>>
        $visitorSession->setCacheByKey('prevEvent', $visitorEventId);
        // todo обновляем кэш <<<

        $cache = $visitorSession->getCache();

        if ($cache['event']['status'] === 'process') {
            $cache['content'] = $content;

            $visitorSession->setCache($cache);
            $visitorEvent->setStatus('new');

            $this->entityManager->persist($visitorSession);
            $this->entityManager->persist($visitorEvent);

            $this->entityManager->flush();

            return;
        }

        $this->rewriteChatEventByScenario($visitorEvent, $visitorSession, $type, $content);
    }

    /**
     * todo по сути, мы тут идём из main-a
     *
     * @throws Exception
     */
    private function createVisitorEventByScenario(VisitorSession $visitorSession, string $type, string $content): void
    {
        $scenario = $this->scenarioService->findScenarioByNameAndType($type, $content);

        $visitorEvent = $this->createEvent($scenario, $type);
        $visitorEventId = $visitorEvent->getId();

        $visitorSession->setVisitorEvent($visitorEventId);

        $this->visitorSessionRepository->save($visitorSession);
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
