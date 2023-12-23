<?php

namespace App\Command;

use App\Dto\Core\Telegram\Webhook\WebhookDto;
use App\Service\Integration\Telegram\TelegramService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'tg:add:webhook',
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
            ->setUrl('https://webhook.site/42761825-a112-43c4-9d94-3208c129efda')
        ;

        $this->telegramService->setWebhook($webhookDto, '5109953245:AAE7TIhplLRxJdGmM27YSeSIdJdOh4ZXVVY');

        return Command::SUCCESS;
    }
}
