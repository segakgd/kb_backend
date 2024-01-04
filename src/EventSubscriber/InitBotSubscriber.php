<?php

namespace App\EventSubscriber;

use App\Dto\Core\Telegram\Webhook\WebhookDto;
use App\Event\InitBotEvent;
use App\Service\Integration\Telegram\TelegramServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InitBotSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TelegramServiceInterface $telegramService
    ){
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InitBotEvent::class => 'initBot',
        ];
    }

    public function initBot(InitBotEvent $event): void
    {
        $dot = $event->getBot();

        $uri = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/webhook/' . $dot->getProjectId() . '/' . $dot->getType() . '/';

        $webhookDto = (new WebhookDto())
            ->setUrl($uri)
        ;

        $this->telegramService->setWebhook($webhookDto, $dot->getToken());
    }
}
