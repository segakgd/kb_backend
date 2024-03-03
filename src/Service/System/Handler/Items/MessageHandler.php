<?php

namespace App\Service\System\Handler\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Helper;
use App\Repository\Scenario\ScenarioRepository;
use App\Repository\User\BotRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\Integration\Telegram\TelegramService;
use App\Service\System\Handler\Contract;
use App\Service\System\Handler\Dto\Contract\ContractMessageDto;
use App\Service\System\Handler\Items\Sub\ChainHandler;
use App\Service\System\Handler\Items\Sub\ScenarioHandler;
use App\Service\Visitor\Scenario\ScenarioService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

class MessageHandler
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly ScenarioService $scenarioService,
        private readonly ScenarioRepository $scenarioRepository,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly ChainHandler $chainHandler,
        private readonly ScenarioHandler $scenarioHandler,
        private readonly EntityManagerInterface $entityManager,
        private readonly BotRepository $botRepository,
        private readonly SerializerInterface $serializer,
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

        $status = $visitorSession->getCacheStatusEvent();
        $content = $visitorSession->getCacheContent();

        $contract = $this->createDefaultContract();

        if ($status === 'process') {
            $contract = $this->chainHandler->handle($contract, $cache, $content, $cacheDto);

            $visitorSession->setCache($cache);
        } else {
            $contract = $this->scenarioHandler->handle($contract, $scenario);
        }

        $this->sendMessages($contract, $token, $visitorSession);

        $cache = $this->serializer->normalize($cacheDto);
        $visitorSession->setCache($cache);

        $this->entityManager->persist($scenario);
        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        return true;
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

    private function createDefaultContract(): Contract
    {
        $contractMessage = Helper::createContractMessage('Дефолтное сообщение...');

        return (new Contract())
            ->addMessage($contractMessage);
    }
}
