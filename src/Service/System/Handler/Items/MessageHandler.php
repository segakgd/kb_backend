<?php

namespace App\Service\System\Handler\Items;

use App\Dto\SessionCache\Cache\CacheDto;
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
    public function handle(VisitorEvent $visitorEvent): bool
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
            $contract = $this->stepHandler->handle(
                $contract,
                $cacheDto,
                $scenarioStep,
                $scenario->getUUID(),
            );
        }

        $this->sendMessages($contract, $token, $visitorSession);

        $cache = $this->serializer->normalize($cacheDto);
        $visitorSession->setCache($cache);

        $this->entityManager->persist($scenario);
        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        return true;
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
