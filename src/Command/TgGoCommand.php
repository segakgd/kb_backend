<?php

namespace App\Command;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Dto\SessionCache\Cache\CacheContractDto;
use App\Entity\Scenario\Scenario;
use App\Enum\VisitorEventStatusEnum;
use App\Helper\CommonHelper;
use App\Repository\User\BotRepository;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\System\Resolver\Dto\BotDto;
use App\Service\System\Resolver\EventResolver;
use App\Service\System\Resolver\Jumps\JumpResolver;
use App\Service\Visitor\Scenario\ScenarioService;
use Doctrine\ORM\EntityManagerInterface;
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
        private readonly EventResolver $eventResolver,
        private readonly ScenarioService $scenarioService,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly BotRepository $botRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly JumpResolver $jumpResolver,
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
            $visitorEvent = $this->visitorEventRepository->findOneByStatus(VisitorEventStatusEnum::New);
        }

        if (!$visitorEvent) {
            return Command::SUCCESS;
        }

        try {
            $responsible = CommonHelper::createDefaultResponsible();

            $visitorSession = $this->visitorSessionRepository->findByEventId($visitorEvent->getId());
            $bot = $this->botRepository->find($visitorSession->getBotId());

            $scenario = $this->scenarioService->findScenarioByUUID($visitorEvent->getScenarioUUID());
            $cacheDto = $visitorSession->getCache();

            $isEmptyContracts = $cacheDto->getEvent()->isEmptyContracts();

            if ($cacheDto->getEvent()->isFinished() || $isEmptyContracts) {
                $cacheDto = $this->enrichContractCache($scenario, $cacheDto);
            }

            $botDto = (new BotDto())
                ->setType($bot->getType())
                ->setToken($bot->getToken())
                ->setChatId($visitorSession->getChatId());

            $responsible->setCacheDto($cacheDto);
            $responsible->setBotDto($botDto);

            $responsible = $this->eventResolver->resolve($visitorEvent, $responsible);

            if (!is_null($responsible->getJump())) {
                $this->jumpResolver->resolveJump(
                    visitorEvent: $visitorEvent,
                    responsible: $responsible
                );
            }

            $visitorSession->setCache($responsible->getCacheDto());

            $this->entityManager->persist($visitorEvent);
            $this->entityManager->persist($visitorSession);
            $this->entityManager->flush();

            $this->visitorEventRepository->updateChatEventStatus($visitorEvent, $responsible->getStatus());
        } catch (Throwable $throwable) {
            $visitorEvent->setError($throwable->getMessage());

            $this->visitorEventRepository->updateChatEventStatus($visitorEvent, VisitorEventStatusEnum::Failed);

            $io->error($throwable->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function enrichContractCache(Scenario $scenario, CacheDto $cacheDto): CacheDto
    {
        $scenarioContract = $scenario->getContracts();
        $cacheContractDto = CacheContractDto::fromArray($scenarioContract->toArray());

        $cacheDto->getEvent()->setContract($cacheContractDto);

        return $cacheDto;
    }
}
