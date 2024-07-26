<?php

namespace App\MessageHandler;

use App\Dto\SessionCache\Cache\CacheContractDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorSession;
use App\Enum\VisitorEventStatusEnum;
use App\Helper\CommonHelper;
use App\Message\TelegramMessage;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Constructor\Core\Dto\BotDto;
use App\Service\Constructor\Core\EventResolver;
use App\Service\Constructor\Core\Jumps\JumpResolver;
use App\Service\Constructor\Visitor\ScenarioManager;
use App\Service\DtoRepository\ResponsibleDtoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
final readonly class TelegramMessageHandler
{
    public function __construct(
        private VisitorEventRepository $visitorEventRepository,
        private EventResolver $eventResolver,
        private ScenarioManager $scenarioService,
        private VisitorSessionRepository $visitorSessionRepository,
        private EntityManagerInterface $entityManager,
        private JumpResolver $jumpResolver,
        private MessageBusInterface $bus,
        private ResponsibleDtoRepository $responsibleDtoRepository,
        private LoggerInterface $logger,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(TelegramMessage $message): void
    {
        $visitorEventId = $message->getVisitorEventId();
        $visitorEvent = $this->visitorEventRepository->find($visitorEventId);

        if (null === $visitorEvent) {
            return;
        }

        if (
            VisitorEventStatusEnum::New === $visitorEvent->getStatus()
            || VisitorEventStatusEnum::Repeat === $visitorEvent->getStatus()
            || VisitorEventStatusEnum::Jumped === $visitorEvent->getStatus()
            || VisitorEventStatusEnum::JumpedToChain === $visitorEvent->getStatus()
        ) {
            try {
                $visitorSession = $this->visitorSessionRepository->find($visitorEvent->getSessionId());

                $cacheDto = $visitorSession->getCache();

                if (
                    VisitorEventStatusEnum::New === $visitorEvent->getStatus()
                    || VisitorEventStatusEnum::Jumped === $visitorEvent->getStatus()
                ) {
                    $scenario = $this->scenarioService->getByUuidOrDefault($visitorEvent->getScenarioUUID());

                    $cacheDto = $this->enrichContractCache($scenario, $cacheDto);
                }

                $responsible = CommonHelper::createDefaultResponsible();

                $responsible->setCacheDto($cacheDto);
                $responsible->setBotDto(
                    botDto: $this->createBotBto($visitorSession)
                );

                $responsible = $this->eventResolver->resolve($responsible);

                if ($responsible->isExistJump()) {
                    $this->jumpResolver->resolveJump(
                        visitorEvent: $visitorEvent,
                        responsible: $responsible
                    );
                }

                $visitorSession->setCache($responsible->getCacheDto());

                $visitorEvent->setStatus($responsible->getStatus());

                $this->entityManager->persist($visitorEvent);
                $this->entityManager->persist($visitorSession);
                $this->entityManager->flush();

                if (isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'dev') {
                    $this->responsibleDtoRepository->save($visitorEvent, $responsible);
                }

                unset($responsible);

                if (
                    VisitorEventStatusEnum::Repeat === $visitorEvent->getStatus()
                    || VisitorEventStatusEnum::Jumped === $visitorEvent->getStatus()
                    || VisitorEventStatusEnum::JumpedToChain === $visitorEvent->getStatus()
                ) {
                    $this->bus->dispatch(new TelegramMessage($visitorEvent->getId()));
                }
            } catch (Throwable $exception) {
                $message = ' MESSAGE: ' . $exception->getMessage() . "\n"
                    . ' FILE: ' . $exception->getFile() . "\n"
                    . ' LINE: ' . $exception->getLine();

                $visitorEvent->setError($message);

                $this->visitorEventRepository->updateChatEventStatus($visitorEvent, VisitorEventStatusEnum::Failed);

                $this->logger->error($exception->getMessage(), $exception->getTrace());

                throw $exception;
            }
        }
    }

    private function createBotBto(VisitorSession $visitorSession): BotDto
    {
        $bot = $visitorSession->getBot();

        return (new BotDto())
            ->setType($bot->getType())
            ->setToken($bot->getToken())
            ->setChatId($visitorSession->getChatId());
    }

    private function enrichContractCache(Scenario $scenario, CacheDto $cacheDto): CacheDto
    {
        $scenarioContract = $scenario->getContract();
        $cacheContractDto = CacheContractDto::fromArray($scenarioContract->toArray());

        $cacheDto->getEvent()->setContract($cacheContractDto);
        $cacheDto->getEvent()->setFinished(false);

        return $cacheDto;
    }
}
