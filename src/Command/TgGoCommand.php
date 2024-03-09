<?php

namespace App\Command;

use App\Entity\Visitor\VisitorEvent;
use App\Helper\CommonHelper;
use App\Repository\User\BotRepository;
use App\Repository\Visitor\VisitorEventRepository;
use App\Service\System\Handler\MessageHandler;
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
        private readonly BotRepository $botRepository,
        private readonly MessageHandler $messageHandler,
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
            $contract = CommonHelper::createDefaultContract();

            $bot = $this->botRepository->find(10);

            $statusEvent = $this->messageHandler->handle($visitorEvent, $contract, $bot);

            $this->visitorEventRepository->updateChatEventStatus($visitorEvent, $statusEvent);

        } catch (Throwable $throwable){
            $visitorEvent->setError($throwable->getMessage());

            $this->visitorEventRepository->updateChatEventStatus($visitorEvent, VisitorEvent::STATUS_FAIL);

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
