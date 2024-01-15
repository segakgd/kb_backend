<?php

namespace App\EventSubscriber;

use App\Dto\Core\Telegram\Webhook\WebhookDto;
use App\Event\InitWebhookBotEvent;
use App\Repository\User\BotRepository;
use App\Service\Integration\Telegram\TelegramServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InitWebhookBotSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TelegramServiceInterface $telegramService,
        private readonly BotRepository $botRepository,
    ){
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InitWebhookBotEvent::class => 'initWebhookBot',
        ];
    }

    public function initWebhookBot(InitWebhookBotEvent $event): void
    {
        $dot = $event->getBot();
        $uri = $dot->getWebhookUri();

        if (!$uri){
            $uri = 'https://mydevbot.ru' . '/webhook/' . $dot->getProjectId() . '/' . $dot->getType() . '/';

            $dot->setWebhookUri($uri);

            $this->botRepository->saveAndFlush($dot);
        }

        $webhookDto = (new WebhookDto())
            ->setUrl($uri)
        ;

        $this->telegramService->setWebhook($webhookDto, $dot->getToken());
    }
}
