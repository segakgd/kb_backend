<?php

namespace App\Command;

use App\Entity\Visitor\VisitorEvent;
use App\Repository\Visitor\VisitorEventRepository;
use App\Service\Admin\History\HistoryService;
use App\Service\Common\History\HistoryErrorService;
use App\Service\System\Handler\ActionHandler;
use App\Service\Visitor\Event\VisitorEventService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly ActionHandler $actionHandler,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('visitorEventId', InputArgument::OPTIONAL, 'Обрабатываем конкретный евент')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $visitorEventId = $input->getArgument('visitorEventId');

        if ($visitorEventId){
            $visitorEvent = $this->visitorEventRepository->findOneById($visitorEventId);
        } else {
            $visitorEvent = $this->visitorEventRepository->findOneByStatus(VisitorEvent::STATUS_NEW);
        }

        if (!$visitorEvent){
            return Command::SUCCESS;
        }

        try {
//            $this->updateChatEventStatus($chatEvent, ChatEvent::STATUS_IN_PROCESS);

            $this->actionHandler->handle($visitorEvent);

//            if ($chatEvent->issetActions()){
//                $this->updateChatEventStatus($chatEvent, ChatEvent::WAITING_ACTION);
//            } else {
//                $this->updateChatEventStatus($chatEvent, ChatEvent::STATUS_DONE);
//            }

            $this->visitorEventService->updateChatEventStatus($visitorEvent, VisitorEvent::STATUS_DONE);

        } catch (Throwable $throwable){
            $visitorEvent->setError($throwable->getMessage());

            $this->visitorEventService->updateChatEventStatus($visitorEvent, VisitorEvent::STATUS_FAIL);

//            HistoryErrorService::errorSystem(
//                $throwable->getMessage(),
//                $visitorEvent->getProjectId(),
//                HistoryService::HISTORY_TYPE_SEND_MESSAGE_TO_CHANNEL
//            );

            $io->error($throwable->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
