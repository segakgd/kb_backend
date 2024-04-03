<?php

namespace App\Command;

use App\Entity\Visitor\VisitorEvent;
use App\Enum\ChainStatusEnum;
use App\Helper\CommonHelper;
use App\Repository\Visitor\VisitorEventRepository;
use App\Service\System\Resolver\EventResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

#[AsCommand(
    name: 'kb:tg:handler_events',
    description: 'Add a short description for your command',
)]
class TgGoCommand extends Command
{
    public function __construct(
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly EventResolver $eventResolver,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument(
            'visitorEventId',
            InputArgument::OPTIONAL,
            'Обрабатываем конкретный евент'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $visitorEventId = $input->getArgument('visitorEventId');

        if ($visitorEventId) {
            $visitorEvent = $this->visitorEventRepository->findOneById($visitorEventId);
        } else {
            $visitorEvent = $this->visitorEventRepository->findOneByStatus(VisitorEvent::STATUS_NEW);
        }

        if (!$visitorEvent) {
            return Command::SUCCESS;
        }

        try {
            $contract = CommonHelper::createDefaultContract();

            $this->eventResolver->resolve($visitorEvent, $contract);

            $this->visitorEventRepository->updateChatEventStatus($visitorEvent, $contract->getStatus());
        } catch (Throwable $throwable) {
            $visitorEvent->setError($throwable->getMessage());

            $this->visitorEventRepository->updateChatEventStatus($visitorEvent, ChainStatusEnum::Failed);

            $io->error($throwable->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
