<?php

namespace App\Command;

use App\Entity\Visitor\VisitorEvent;
use App\Helper\CommonHelper;
use App\Repository\Visitor\VisitorEventRepository;
use App\Service\System\Resolver\EventResolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

use function sleep;

#[AsCommand(
    name: 'kb:tg:events:handler',
    description: 'Add a short description for your command',
)]
class TelegramEventsHandlerCommand extends Command
{
    public function __construct(
        private readonly VisitorEventRepository $visitorEventRepository,
        private readonly EventResolver $eventResolver,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Начал выполнять команду');

        for (; ;) {
            $visitorEvent = $this->visitorEventRepository->findOneByStatus(VisitorEvent::STATUS_NEW);

            if ($visitorEvent) {
                try {
                    $contract = CommonHelper::createDefaultContract();

                    $this->eventResolver->resolve($visitorEvent, $contract);

                    $this->visitorEventRepository->updateChatEventStatus($visitorEvent, $contract->getStatus());
                } catch (Throwable $throwable) {
                    $visitorEvent->setError($throwable->getMessage());

                    $this->visitorEventRepository->updateChatEventStatus($visitorEvent, VisitorEvent::STATUS_FAIL);

                    $io->error($throwable->getMessage());

                    return Command::FAILURE;
                }

                sleep(1);
                $output->write('+');
            }

            sleep(1);
            $output->write('-');
        }
    }
}
