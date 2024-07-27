<?php

namespace App\MessageHandler;

use App\Dto\SessionCache\Cache\CacheContractDto;
use App\Entity\Scenario\Scenario;
use App\Entity\SessionCache;
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
        $event = $this->visitorEventRepository->find($visitorEventId);

        if (null === $event) {
            return;
        }

        if (
            VisitorEventStatusEnum::New === $event->getStatus()
            || VisitorEventStatusEnum::Repeat === $event->getStatus()
            || VisitorEventStatusEnum::Jumped === $event->getStatus()
            || VisitorEventStatusEnum::JumpedToChain === $event->getStatus()
        ) {
            try {
                $session = $this->visitorSessionRepository->find($event->getSessionId());

                $sessionCache = $session->getCache();

                if (
                    VisitorEventStatusEnum::New === $event->getStatus()
                    || VisitorEventStatusEnum::Jumped === $event->getStatus()
                ) {
                    $scenario = $this->scenarioService->getByUuidOrDefault($event->getScenarioUUID());

                    $sessionCache = $this->enrichContractCache($scenario, $sessionCache);
                }

                $responsible = CommonHelper::createDefaultResponsible();

                $responsible->setCacheDto($sessionCache);
                $responsible->setBotDto(
                    botDto: $this->createBotBto($session)
                );

                $responsible = $this->eventResolver->resolve($responsible);

                if ($responsible->isExistJump()) {
                    $this->jumpResolver->resolveJump(
                        visitorEvent: $event,
                        responsible: $responsible
                    );
                }

                $event->setStatus($responsible->getStatus());

                $this->entityManager->persist($event);
                $this->entityManager->persist($session);
                $this->entityManager->persist($sessionCache);
                $this->entityManager->flush();

                if (isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'dev') {
                    $this->responsibleDtoRepository->save($event, $responsible);
                }

                unset($responsible);

                if (
                    VisitorEventStatusEnum::Repeat === $event->getStatus()
                    || VisitorEventStatusEnum::Jumped === $event->getStatus()
                    || VisitorEventStatusEnum::JumpedToChain === $event->getStatus()
                ) {
                    $this->bus->dispatch(new TelegramMessage($event->getId()));
                }
            } catch (Throwable $exception) {
                $message = ' MESSAGE: ' . $exception->getMessage() . "\n"
                    . ' FILE: ' . $exception->getFile() . "\n"
                    . ' LINE: ' . $exception->getLine();

                $event->setError($message);

                $this->visitorEventRepository->updateChatEventStatus($event, VisitorEventStatusEnum::Failed);

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

    private function enrichContractCache(Scenario $scenario, SessionCache $sessionCache): SessionCache
    {
        $scenarioContract = $scenario->getContract();
        $cacheContractDto = CacheContractDto::fromArray($scenarioContract->toArray());

        $sessionCache->getEvent()->setContract($cacheContractDto);
        $sessionCache->getEvent()->setFinished(false);

        return $sessionCache;
    }
}
