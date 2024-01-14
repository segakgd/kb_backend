<?php

namespace App\EventSubscriber;

use App\Dto\Core\Telegram\Webhook\WebhookDto;
use App\Event\InitBotEvent;
use App\Exception\History\HistoryExceptionInterface;
use App\Service\Admin\History\HistoryServiceInterface;
use App\Service\Integration\Telegram\TelegramServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class HistorySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly HistoryServiceInterface $historyService,
    ){
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'historyEventsCheck',
        ];
    }

    public function historyEventsCheck(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof HistoryExceptionInterface)
        {
            return;
        }

        $this->historyService->add(
            $exception->getProjectId(),
            $exception->getType(),
            $exception->getStatus(),
            $exception->getSender(),
            $exception->getRecipient(),
            $exception->getError(),
            $exception->getCreatedAt()
        );
    }
}
