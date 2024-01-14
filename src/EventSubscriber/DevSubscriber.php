<?php

namespace App\EventSubscriber;

use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Не старался, нужно было быстро и просто
 */
class DevSubscriber implements EventSubscriberInterface
{
    private const ALLOWED_IP = [
        '178.68.18.84',
    ];

    private const OPEN_UR = [
        '/webhook/4842/telegram/',
    ];

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'checkRequest',
        ];
    }

    /**
     * @throws Exception
     */
    public function checkRequest(RequestEvent $event): void
    {
        if (!$this->checkUri($event) && !$this->checkIp($event)){
            $event->setResponse(new Response(null, Response::HTTP_NOT_FOUND));
        }
    }

    private function checkIp(RequestEvent $event): bool
    {
        return in_array($event->getRequest()->getClientIp(), self::ALLOWED_IP);
    }

    private function checkUri(RequestEvent $event): bool
    {
        return in_array($event->getRequest()->getRequestUri(), self::OPEN_UR);
    }
}
