<?php

namespace App\Service\Visitor\Event;

use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Admin\Scenario\BehaviorScenarioService;
//use App\Service\System\Handler\ActionAfterHandler;
//use App\Service\System\Handler\ActionBeforeHandler;
use App\Service\Visitor\Session\VisitorSessionServiceInterface;
use Exception;

class VisitorEventService
{
    public function __construct(
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly VisitorSessionServiceInterface $visitorSessionService,
        private readonly BehaviorScenarioService $behaviorScenarioService,
//        private readonly ActionAfterHandler $actionAfterHandler,
//        private readonly ActionBeforeHandler $actionBeforeHandler,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createChatEventForSession(VisitorSession $visitorSession, string $type, string $content): void
    {
        $visitorEventId = $visitorSession->getChatEvent();

        if ($visitorEventId){

            // todo вот какая не очевидная ситуация... почему в этом блоке проверка только на null...
            //  если null, то проскакиваем за пределы if-а и доходим до вызова createVisitorEventByScenario... -_-
            $visitorEvent = $this->visitorEventRepository->find($visitorEventId);

//            if (null !== $visitorEvent && $visitorEvent->issetActions()){
//                if ($visitorEvent->getActionAfter()){ // todo это внутренние события. их нужно обрабатывать паралельно?
//                    $this->actionAfterHandler->handle();
//                }
//
//                if ($visitorEvent->getActionBefore()){ // todo это внутренние события. их нужно обрабатывать паралельно?
//                    $this->actionBeforeHandler->handle();
//                }
//
//                $visitorEvent->setStatus(VisitorEvent::STATUS_DONE); // todo почему done?
//
//                $this->visitorEventRepository->saveAndFlush($visitorEvent);
//            }

//            // если мы находимся тут, это значит что пора проверить, можем ли мы затереть собитие, которое ожидает что-то или нет.
//            if (null !== $visitorEvent && $this->isMandatoryEvent($visitorEvent, $type)){
//                throw new Exception('Событие обязательно, нужно уведомить пользователя об этом');
//            }

            // todo ааа... понял. типа мы перезаписываем старое событие новым...
            if (null !== $visitorEvent){
                $this->rewriteChatEventByScenario($visitorEvent, $visitorSession, $type, $content);

                return;
            }
        }

        $this->createVisitorEventByScenario($visitorSession, $type, $content);
    }

    /**
     * @throws Exception
     */
    private function createVisitorEventByScenario(VisitorSession $visitorSession, string $type, string $content): void
    {
        $scenario = $this->behaviorScenarioService->getScenarioByNameAndType($type, $content);

        if (null === $scenario){
            throw new Exception('Не существует ни одного сценария'); // todo может быть такое, что $scenario не существет
        }

        $visitorEvent = $this->createChatEvent($scenario, $type);
        $visitorEventId = $visitorEvent->getId();

        $visitorSession->setChatEvent($visitorEventId);

        $this->visitorSessionRepository->save($visitorSession);
    }

    private function rewriteChatEventByScenario(
        VisitorEvent $visitorEvent,
        VisitorSession $visitorSession,
        string $type,
        string $content,
    ): void {
        $scenario = $this->behaviorScenarioService->getScenarioByNameAndType($type, $content);

        if (!$scenario){ // todo что тут проиходит?
            $ownerBehaviorScenarioId = $visitorEvent->getBehaviorScenario();
            $scenario = $this->behaviorScenarioService->getScenarioByOwnerId($ownerBehaviorScenarioId);
        }

        if (!$scenario){ // todo как сюда дойти? оО
            $scenario = $this->behaviorScenarioService->generateDefaultScenario();
        }

        // один и тот же сценарий, нет смысла перезатирать
        if ($visitorEvent->getBehaviorScenario() === $scenario->getId()){
            return;
        }

        $oldEventId = $visitorSession->getChatEvent();
        $visitorEvent = $this->createChatEvent($scenario, $type);

        $this->visitorSessionService->rewriteChatEvent($visitorSession, $visitorEvent->getId());
        $this->visitorEventRepository->removeById($oldEventId);
    }

    private function createChatEvent(Scenario $scenario, string $type): VisitorEvent
    {
        $visitorEvent = (new VisitorEvent())
            ->setType($type)
            ->setBehaviorScenario($scenario->getId())
            ->setActionAfter($scenario->getActionAfter() ?? null)
        ;

        $this->visitorEventRepository->saveAndFlush($visitorEvent);

        return $visitorEvent;
    }

//    /** является обязательным мероприятием */
//    private function isMandatoryEvent(VisitorEvent $visitorEvent, string $type): bool // todo не понимаю эту идею
//    {
//        if ($type === 'command') {
//            return false;
//        }
//
//        if (empty($visitorEvent->getActionAfter())) {
//            return false;
//        }
//
//        if (VisitorEvent::STATUS_DONE === $visitorEvent->getStatus()) {
//            return false;
//        }
//
//        return true;
//    }
}
