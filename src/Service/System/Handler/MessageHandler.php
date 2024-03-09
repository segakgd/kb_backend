<?php

namespace App\Service\System\Handler;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\User\Bot;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
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
        private readonly CacheService $cacheService,
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

            return;
        }

        $this->senderService->sendMessages($contract, $bot->getToken(), $visitorSession);

        $this->insertCacheDtoFromSession($visitorSession, $cacheDto);

        $this->entityManager->persist($visitorEvent);
        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        $contract->setStatus(
            $cacheDto->getEvent()->isFinished() ? VisitorEvent::STATUS_DONE : VisitorEvent::STATUS_AWAIT
        );
    }

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
        $scenario = $this->scenarioService->getMainScenario();

        $visitorEvent->setScenarioUUID($scenario->getUUID());

        $cacheDto->setEvent(CacheService::createCacheEventDto());

        $this->insertCacheDtoFromSession($visitorSession, $cacheDto);

        $this->entityManager->persist($visitorEvent);
        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        $contract->setStatus(VisitorEvent::STATUS_NEW);
    }

    private function getCacheDtoFromSession(VisitorSession $visitorSession): CacheDto
    {
        $cache = $visitorSession->getCache();

        /** @var CacheDto $cacheDto */
        return $this->serializer->denormalize($cache, CacheDto::class);
    }

    private function insertCacheDtoFromSession(VisitorSession $visitorSession, CacheDto $cacheDto): void
    {
        $cache = $this->serializer->normalize($cacheDto);
        $visitorSession->setCache($cache);
    }
}
