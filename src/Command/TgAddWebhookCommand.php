<?php

namespace App\Command;

use App\Event\InitWebhookBotEvent;
use App\Repository\User\BotRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
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
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly BotRepository $botRepository,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $myProjectId = 4842;

        $bot = $this->botRepository->findOneBy(
            [
                'projectId' => $myProjectId,
                'name' => 'Bot first',
                'type' => 'telegram',
            ]
        );

        $this->eventDispatcher->dispatch(new InitWebhookBotEvent($bot));

        return Command::SUCCESS;
    }
}
