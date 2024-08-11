<?php

namespace App\MessageHandler;

use App\Dto\SessionCache\Cache\CacheContractDto;
use App\Entity\Scenario\Scenario;
use App\Entity\SessionCache;
use App\Entity\Visitor\Event;
use App\Entity\Visitor\Session;
use App\Enum\VisitorEventStatusEnum;
use App\Helper\CommonHelper;
use App\Message\SendTelegramMessage;
use App\Message\TelegramMessage;
use App\Repository\SessionCacheRepository;
use App\Repository\Visitor\VisitorEventRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Constructor\Core\Dto\Responsible;
use App\Service\Constructor\Core\EventResolver;
use App\Service\Constructor\Visitor\ScenarioManager;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
final readonly class TelegramMessageHandler
{
    public function __construct(
        private EventResolver $eventResolver,
        private ScenarioManager $scenarioService,
        private SessionCacheRepository $sessionCacheRepository,
        private VisitorEventRepository $visitorEventRepository,
        private VisitorSessionRepository $visitorSessionRepository,
        private MessageBusInterface $bus,
        private LoggerInterface $logger,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(TelegramMessage $message): void
    {
        $eventId = $message->getVisitorEventId();
        $event = $this->visitorEventRepository->find($eventId);

        try {
            if (null === $event) {
                return;
            }

            if (!$event->isStatusAvailableForHandle()) {
                return;
            }

            $session = $this->visitorSessionRepository->find($event->getSessionId());

            $sessionCache = $session->getCache();
            $sessionCache = $this->enrichContractCacheIfNeed($event, $sessionCache);

            $responsible = CommonHelper::createDefaultResponsible($session, $sessionCache);

            // вот кейсы:
            // отлавливаем jump до того как начнём обрабатывать событие. (системный jump)
            // отлавливаем jump после обработки события (пользовательский jump) - todo а такая вообще возможно? Оо
            // отлавливаем jumpToChain после обработки события - вполне рядовая ситуация

            $responsible = $this->eventResolver->resolve($responsible);

            $this->updateEntities($event, $session, $sessionCache, $responsible);

            $this->bus->dispatch(new SendTelegramMessage(
                session: $session,
                result: $responsible->getResult(),
                botDto: $responsible->getBot(),
            ));

            if ($event->isRepeatStatuses()) {
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

    private function updateEntities(
        Event $event,
        Session $session,
        SessionCache $sessionCache,
        Responsible $responsible,
    ): void {
        $event->setStatus($responsible->getStatus());

        $this->visitorEventRepository->saveAndFlush($event);
        $this->visitorSessionRepository->saveAndFlush($session);
        $this->sessionCacheRepository->saveAndFlush($sessionCache);

        $event->setResponsible($responsible);
    }

    /**
     * @throws Exception
     */
    private function enrichContractCacheIfNeed(Event $event, SessionCache $sessionCache): SessionCache
    {
        if (
            VisitorEventStatusEnum::New === $event->getStatus()
        ) {
            $scenario = $this->scenarioService->getByUuidOrDefault($event->getScenarioUUID());

            $sessionCache = $this->enrichContractCache($scenario, $sessionCache);
        }

        return $sessionCache;
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
