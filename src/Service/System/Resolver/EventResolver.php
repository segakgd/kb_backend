<?php

namespace App\Service\System\Resolver;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\User\Bot;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Repository\User\BotRepository;
use App\Repository\Visitor\VisitorSessionRepository;
use App\Service\DtoRepository\ContractDtoRepository;
use App\Service\DtoRepository\SessionCacheDtoRepository;
use App\Service\System\Common\SenderService;
use App\Service\System\Contract;
use App\Service\Visitor\Scenario\ScenarioService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class EventResolver
{
    public function __construct(
        private readonly ScenarioService $scenarioService,
        private readonly VisitorSessionRepository $visitorSessionRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly StepResolver $stepResolver,
        private readonly SenderService $senderService,
        private readonly GotoResolver $gotoResolver,
        private readonly BotRepository $botRepository,
        private readonly SessionCacheDtoRepository $cacheDtoRepository,
        private readonly ContractDtoRepository $contractDtoRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function resolve(VisitorEvent $visitorEvent, Contract $contract): void
    {
        $visitorSession = $this->visitorSessionRepository->findByEventId($visitorEvent->getId());
        $bot = $this->botRepository->find($visitorSession->getBotId());

        $scenario = $this->scenarioService->findScenarioByUUID($visitorEvent->getScenarioUUID());
        $cacheDto = $this->cacheDtoRepository->get($visitorSession);
        $this->stepResolver->resolve($scenario->getSteps(), $contract, $cacheDto);

        if ($contract->isNotGoto()) {
            $this->sendMessageAndMoveStatus(
                contract: $contract,
                bot: $bot,
                visitorSession: $visitorSession,
                cacheDto: $cacheDto
            );
        } else {
            $this->gotoResolver->goto(
                visitorEvent: $visitorEvent,
                cacheDto: $cacheDto,
                visitorSession: $visitorSession,
                contract: $contract
            );
        }

        if (isset($_SERVER['APP_ENV']) && $_SERVER['APP_ENV'] === 'dev') {
            $this->contractDtoRepository->save($visitorEvent, $contract);
        }

        $this->entityManager->persist($visitorEvent);
        $this->entityManager->persist($visitorSession);
        $this->entityManager->flush();
    }

    /**
     * @throws Exception
     */
    private function sendMessageAndMoveStatus(
        Contract $contract,
        Bot $bot,
        VisitorSession $visitorSession,
        CacheDto $cacheDto,
    ): void {
        $this->senderService->sendMessages($contract, $bot->getToken(), $visitorSession);

        $status = $cacheDto->getEvent()->isFinished() ? VisitorEvent::STATUS_DONE : VisitorEvent::STATUS_AWAIT;

        $contract->setStatus($status);

        if ($contract->isStatusDone()) {
            $cacheDto->clearEvent();
        }

        $this->cacheDtoRepository->save($visitorSession, $cacheDto);
    }
}
