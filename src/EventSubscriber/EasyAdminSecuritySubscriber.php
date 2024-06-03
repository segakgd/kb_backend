<?php

namespace App\EventSubscriber;

use App\Controller\Dev\Admin\DashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class EasyAdminSecuritySubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'initWebhookBot',
        ];
    }

    public function initWebhookBot(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        $controller = $controller[0];

        if ($controller instanceof AbstractCrudController) {
            $this->check();
        }

        if ($controller instanceof DashboardController) {
            $this->check();
        }
    }

    private function check(): void
    {
        if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Access denied.');
        }
    }
}
