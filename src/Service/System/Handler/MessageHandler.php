<?php

namespace App\Service\System\Handler;

use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Entity\User\Bot;
use App\Entity\Visitor\VisitorEvent;
use App\Repository\Visitor\VisitorSessionRepository;
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

        $cache = $visitorSession->getCache();

        /** @var CacheDto $cacheDto */
        $cacheDto = $this->serializer->denormalize($cache, CacheDto::class);

        $this->handleSteps($scenario->getSteps(), $contract, $cacheDto);

        if ($contract->getGoto()) {
            $this->goto($visitorEvent, $cacheDto, $visitorSession, $contract);

            return;
        }

        $this->senderService->sendMessages($contract, $bot->getToken(), $visitorSession);

        $statusEvent = $cacheDto->getEvent()->isFinished();

        $cache = $this->serializer->normalize($cacheDto);
        $visitorSession->setCache($cache);

        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();

        $contract->setStatus($statusEvent ? VisitorEvent::STATUS_DONE : VisitorEvent::STATUS_AWAIT);
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

    private function goto($visitorEvent, $cacheDto, $visitorSession, $contract): void
    {
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

        $contract->setStatus(VisitorEvent::STATUS_NEW);
    }
}
