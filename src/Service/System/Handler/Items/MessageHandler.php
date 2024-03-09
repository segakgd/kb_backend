<?php

namespace App\Service\System\Handler\Items;

use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Repository\User\BotRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\System\Handler\Contract;
use App\Service\System\Handler\Dto\Contract\ContractMessageDto;
use App\Service\System\Handler\Items\Sub\StepHandler;
use App\Service\Visitor\Scenario\ScenarioService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

class MessageHandler
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly ScenarioService $scenarioService,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly BotRepository $botRepository,
        private readonly SerializerInterface $serializer,
        private readonly StepHandler $stepHandler,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(VisitorEvent $visitorEvent): string
    {
        $bot = $this->botRepository->find(10);
        $token = $bot->getToken();

        $scenario = $this->scenarioService->findScenarioByUUID($visitorEvent->getScenarioUUID());

        $visitorSession = $this->visitorSessionRepository->findByEventId($visitorEvent->getId());

        $cache = $visitorSession->getCache();

        /** @var CacheDto $cacheDto */
        $cacheDto = $this->serializer->denormalize($cache, CacheDto::class);

        $contract = $this->createDefaultContract();

        $scenarioSteps = $scenario->getSteps();

        foreach ($scenarioSteps as $scenarioStep) {
            // todo а если несколько?

            $contract = $this->stepHandler->handle(
                $contract,
                $cacheDto,
                $scenarioStep,
            );
        }

        if ($contract->getGoto()) {
            $scenario = $this->scenarioService->getMainScenario();

            $visitorEvent->setScenarioUUID($scenario->getUUID());

            $cacheEventDto = (new CacheEventDto())
                ->setFinished(false)
                ->setChains([])
                ->setData(
                    (new CacheDataDto)
                        ->setProductId(null)
                        ->setPageNow(null)
                )
            ;

            $cacheDto->setEvent($cacheEventDto);

            $cache = $this->serializer->normalize($cacheDto);
            $visitorSession->setCache($cache);

            $this->entityManager->persist($visitorEvent);
            $this->entityManager->persist($visitorSession);
            $this->entityManager->flush();

            return VisitorEvent::STATUS_NEW;
        }

        $this->sendMessages($contract, $token, $visitorSession);

        $statusEvent = $cacheDto->getEvent()->isFinished();

        $cache = $this->serializer->normalize($cacheDto);
        $visitorSession->setCache($cache);

        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        return $statusEvent ? VisitorEvent::STATUS_DONE : VisitorEvent::STATUS_AWAIT;
    }

    private function createDefaultContract(): Contract
    {
        return (new Contract());
    }

    /**
     * @throws Exception
     */
    private function sendMessages(Contract $contract, string $token, VisitorSession $visitorSession): void
    {
        $messages = $contract->getMessages();

        /** @var ContractMessageDto $message */
        foreach ($messages as $message) {
            if ($message->getPhoto()) {
                $this->telegramService->sendPhoto(
                    $message,
                    $token,
                    $visitorSession->getChatId()
                );

                continue;
            }

            if ($message->getMessage()) {
                $this->telegramService->sendMessage(
                    $message,
                    $token,
                    $visitorSession->getChatId()
                );
            } else {
                throw new Exception('not found message');
            }
        }
    }
}
