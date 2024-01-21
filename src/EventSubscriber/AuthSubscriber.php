<?php

namespace App\EventSubscriber;

use App\Entity\User\User;
use App\Service\Common\History\HistoryEventService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;

class AuthSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly HistoryEventService $historyEventService,
    ){
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'authUser',
        ];
    }

    public function authUser(AuthenticationSuccessEvent $event)
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();

        foreach ($user->getProjects() as $project){
            $this->historyEventService->loginEvent($project->getId());
        }
    }
}
