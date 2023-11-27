<?php

namespace App\Service\Visitor\Event;

use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Admin\Scenario\BehaviorScenarioService;
use App\Service\System\Handler\ActionAfterHandler;
use App\Service\System\Handler\ActionBeforeHandler;
use Exception;

class VisitorEventService
{
    public function __construct(
        private readonly VisitorEventRepository $chatEventRepository,
        private readonly VisitorSessionRepository $chatSessionRepository,
        private readonly BehaviorScenarioService $behaviorScenarioService,
        private readonly ActionAfterHandler $actionAfterHandler,
        private readonly ActionBeforeHandler $actionBeforeHandler,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createChatEventForSession(VisitorSession $chatSession, string $type, string $content): void
    {
        $chatEventId = $chatSession->getChatEvent();

        if ($chatEventId){
            $chatEvent = $this->chatEventRepository->find($chatEventId);

            if (null !== $chatEvent && $chatEvent->issetActions()){
                if ($chatEvent->getActionAfter()){ // todo это внутренние события. их нужно обрабатывать паралельно?
                    $this->actionAfterHandler->handle();
                }

                if ($chatEvent->getActionBefore()){ // todo это внутренние события. их нужно обрабатывать паралельно?
                    $this->actionBeforeHandler->handle();
                }

                $chatEvent->setStatus(VisitorEvent::STATUS_DONE); // todo почему done?

                $this->chatEventRepository->saveAndFlush($chatEvent);
            }

            // если мы находимся тут, это значит что пора проверить, можем ли мы затереть собитие, которое ожидает что-то или нет.
            if (null !== $chatEvent && $this->isMandatoryEvent($chatEvent, $type)){
                throw new Exception('Событие обязательно, нужно уведомить пользователя об этом');
            }

            if (null !== $chatEvent){
                $this->rewriteChatEventByScenario($chatEvent, $chatSession, $type, $content);

                return;
            }
        }

        $this->createChatEventByScenario($chatSession, $type, $content);
    }

    private function createChatEventByScenario(VisitorSession $chatSession, string $type, string $content): void
    {
        $scenario = $this->behaviorScenarioService->getScenarioByNameAndType($type, $content);

        $chatEvent = $this->createChatEvent($scenario, $type);
        $chatEventId = $chatEvent->getId();

        $chatSession->setChatEvent($chatEventId);

        $this->chatSessionRepository->save($chatSession);

    }

    private function rewriteChatEventByScenario(
        VisitorEvent $chatEvent,
        VisitorSession $chatSession,
        string $type,
        string $content
    ): void {
        $scenario = $this->behaviorScenarioService->getScenarioByNameAndType($type, $content);

        if (!$scenario){
            $ownerBehaviorScenarioId = $chatEvent->getBehaviorScenario();
            $scenario = $this->behaviorScenarioService->getScenarioByOwnerId($ownerBehaviorScenarioId);
        }

        if (!$scenario){
            $scenario = $this->behaviorScenarioService->generateDefaultScenario();
        }

        $chatEvent = $this->createChatEvent($scenario, $type);

        $oldEventId = $chatSession->getChatEvent();
        $chatEventId = $chatEvent->getId();

        // обновляем сессию
        $chatSession->setChatEvent($chatEventId);

        $this->chatSessionRepository->save($chatSession);

        // старое событие удаляем
        $oldEvent = $this->chatEventRepository->find($oldEventId);
        $this->chatEventRepository->remove($oldEvent);
    }

    private function createChatEvent(Scenario $scenario, string $type): VisitorEvent
    {
        $chatEvent = (new VisitorEvent())
            ->setType($type)
            ->setBehaviorScenario($scenario->getId())
            ->setActionAfter($scenario->getActionAfter() ?? null)
        ;

        $this->chatEventRepository->saveAndFlush($chatEvent);

        return $chatEvent;
    }

    private function isMandatoryEvent(VisitorEvent $chatEvent, string $type): bool
    {
        if ($type === 'command') {
            return false;
        }

        if (empty($chatEvent->getActionAfter())) {
            return false;
        }

        if (VisitorEvent::STATUS_DONE === $chatEvent->getStatus()) {
            return false;
        }

        return true;
    }
}
