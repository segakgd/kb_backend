<?php

namespace App\EventSubscriber;

use App\Dto\Core\Telegram\Request\Webhook\WebhookDto;
use App\Event\InitWebhookBotEvent;
use App\Repository\User\BotRepository;
use App\Service\Integration\Telegram\TelegramServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class InitWebhookBotSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private TelegramServiceInterface $telegramService,
        private BotRepository $botRepository,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            InitWebhookBotEvent::class => 'initWebhookBot',
        ];
    }

    public function initWebhookBot(InitWebhookBotEvent $event): void
    {
        $dot = $event->getBot();

        $uri = 'https://mydevbot.ru/webhook/' . $dot->getProjectId() . '/' . $dot->getType()->value . '/';
        $dot->setWebhookUri($uri);

        $this->botRepository->saveAndFlush($dot);

        $webhookDto = (new WebhookDto())
            ->setUrl($dot->getWebhookUri());

        $this->telegramService->setWebhook($webhookDto, $dot->getToken());
    }
}
