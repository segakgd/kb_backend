<?php

namespace App\Command;

use App\Dto\Core\Telegram\Webhook\WebhookDto;
use App\Service\Integration\Telegram\TelegramService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'kb:tg:add:webhook',
    description: 'Add webhook',
)]
class TgAddWebhookCommand extends Command
{
    public function __construct(
        private readonly TelegramService $telegramService,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $webhookDto = (new WebhookDto())
            ->setUrl('https://webhook.site/a7768496-d271-465a-a4c6-2c1bd3c08e48')
        ;

        $this->telegramService->setWebhook($webhookDto, '6722125407:AAEDDnc7qpbaZpZg-wpfXQ5h7Yp5mhJND0U');

        return Command::SUCCESS;
    }
}
