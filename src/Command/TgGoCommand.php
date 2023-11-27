<?php

namespace App\Command;

use App\Entity\Visitor\VisitorEvent;
use App\Repository\Visitor\VisitorEventRepository;
use App\Service\System\Handler\ActionHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'tg:go',
    description: 'Add a short description for your command',
)]
class TgGoCommand extends Command
{
    public function __construct(
        private VisitorEventRepository $chatEventRepository, // todo использовать сервис
        private ActionHandler $actionHandler,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $chatEvent = $this->chatEventRepository->findOneBy(
            [
                'status' => VisitorEvent::STATUS_NEW,
            ]
        );

        if (!$chatEvent){
            return Command::SUCCESS;
        }

        try {
//            $this->updateChatEventStatus($chatEvent, ChatEvent::STATUS_IN_PROCESS);

            $this->actionHandler->handle($chatEvent);

//            if ($chatEvent->issetActions()){
//                $this->updateChatEventStatus($chatEvent, ChatEvent::WAITING_ACTION);
//            } else {
//                $this->updateChatEventStatus($chatEvent, ChatEvent::STATUS_DONE);
//            }

        } catch (Throwable $throwable){

            $this->updateChatEventStatus($chatEvent, VisitorEvent::STATUS_FAIL);

            $io->error($throwable->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function updateChatEventStatus(VisitorEvent $chatEvent, string $status): void
    {
        $chatEvent->setStatus($status);

        $this->chatEventRepository->saveAndFlush($chatEvent);
    }
}
