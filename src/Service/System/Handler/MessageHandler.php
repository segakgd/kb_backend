<?php

namespace App\Service\System\Handler;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\User\Bot;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Enum\GotoChainsEnum;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\System\Common\CacheService;
use App\Service\System\Common\SenderService;
use App\Service\System\Contract;
use App\Service\System\Handler\Step\StepHandler;
use App\Service\Visitor\Scenario\ScenarioService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

class MessageHandler
{
    public function __construct(
        private readonly ScenarioService $scenarioService,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
        private readonly StepHandler $stepHandler,
        private readonly SenderService $senderService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(VisitorEvent $visitorEvent, Contract $contract, Bot $bot): void
    {
        $scenario = $this->scenarioService->findScenarioByUUID($visitorEvent->getScenarioUUID());
        $visitorSession = $this->visitorSessionRepository->findByEventId($visitorEvent->getId());

        $cacheDto = $this->getCacheDtoFromSession($visitorSession);

        $this->handleSteps($scenario->getSteps(), $contract, $cacheDto);

        if ($contract->getGoto()) {
            $this->goto($visitorEvent, $cacheDto, $visitorSession, $contract);
        } else {
            $this->senderService->sendMessages($contract, $bot->getToken(), $visitorSession);

            $contract->setStatus(
                $cacheDto->getEvent()->isFinished() ? VisitorEvent::STATUS_DONE : VisitorEvent::STATUS_AWAIT
            );

            if ($contract->isStatusDone()) {
                $cacheDto->setEvent(CacheService::createCacheEventDto());
            }

            $this->insertCacheDtoFromSession($visitorSession, $cacheDto);
        }

        // todo is dev
        $visitorEvent->setContract($this->serializer->normalize($contract));

        $this->entityManager->persist($visitorEvent);
        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();
    }

    private function getCacheDtoFromSession(VisitorSession $visitorSession): CacheDto
    {
        $cache = $visitorSession->getCache();

        /** @var CacheDto $cacheDto */
        return $this->serializer->denormalize($cache, CacheDto::class);
    }

    // todo задача:
    //  1. к сценарию нужно добавить alias, и искать именно по нему, а не по наванию!
    //  2. переделать Chain-ы каждый Chain должен уметь работать самостоятельно, без поддержки другого
    //      т.е цвенья цепи должны быть сомостоятельны.

    /**
     * @throws Exception
     */
    private function handleSteps(array $steps, Contract $contract, CacheDto $cacheDto): void
    {
        foreach ($steps as $step) {
            // todo а если несколько?

            $this->stepHandler->handle(
                $contract,
                $cacheDto,
                $step,
            );
        }
    }

    private function goto(
        VisitorEvent $visitorEvent,
        CacheDto $cacheDto,
        VisitorSession $visitorSession,
        Contract $contract
    ): void {
        $enum = GotoChainsEnum::tryFrom($contract->getGoto());

        if ($enum) {
            $chains = $cacheDto->getEvent()->getChains();

            $flag = true;

            /** @var CacheChainDto $chain */
            foreach ($chains as $chain) {
                if ($chain->getTarget() === $enum) {
                    $chain->setRepeat(true);

                    $flag = false;
                }

                $chain->setFinished($flag);
            }
        } else {
            $scenario = match ($contract->getGoto()) {
                'main' => $this->scenarioService->getMainScenario(),
                'cart' => $this->scenarioService->getCartScenario(),
                default => $this->scenarioService->getDefaultScenario(),
            };

            $visitorEvent->setScenarioUUID($scenario->getUUID());

            $cacheDto->setEvent(CacheService::createCacheEventDto());
        }

        $this->insertCacheDtoFromSession($visitorSession, $cacheDto);

        $contract->setStatus(VisitorEvent::STATUS_NEW);
    }

    private function insertCacheDtoFromSession(VisitorSession $visitorSession, CacheDto $cacheDto): void
    {
        $cache = $this->serializer->normalize($cacheDto);
        $visitorSession->setCache($cache);
    }
}
