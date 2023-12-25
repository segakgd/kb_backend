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
    name: 'kb:tg:handler_events',
    description: 'Add a short description for your command',
)]
class TgGoCommand extends Command
{
    public function __construct(
        private readonly VisitorEventRepository $visitorEventRepository, // todo использовать сервис
        private readonly ActionHandler $actionHandler,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $chatEvent = $this->visitorEventRepository->findOneBy(
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

            $this->updateChatEventStatus($chatEvent, VisitorEvent::STATUS_DONE);

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

        $this->visitorEventRepository->saveAndFlush($chatEvent);
    }
}
