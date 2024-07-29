<?php

namespace App\Service\Constructor\Core\Jumps;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Entity\Visitor\VisitorEvent;
use App\Enum\VisitorEventStatusEnum;
use App\Helper\CacheHelper;
use App\Service\Constructor\Core\Dto\Responsible;
use App\Service\Constructor\Visitor\Scenario\ScenarioService;
use App\Service\DtoRepository\ResponsibleDtoRepository;
use Exception;

readonly class JumpResolver
{
    public function __construct(
        private ScenarioService $scenarioService,
        private ResponsibleDtoRepository $responsibleDtoRepository,
    ) {}

    /**
     * @throws Exception
     */
    public function resolveJump(
        VisitorEvent $visitorEvent,
        Responsible $responsible,
    ): void {
        $jump = $responsible->getJump();

        if (!$jump) {
            throw new Exception('Непредвиденная ситуация: переход не существует.');
        }

        $scenario = $this->scenarioService->findScenarioByTarget($responsible->getJump());

        if ($scenario) {
            $visitorEvent->setScenarioUUID($scenario->getUUID());

            $responsible->setEvent(CacheHelper::createCacheEventDto());

            $this->responsibleDtoRepository->save($visitorEvent, $responsible);

            $responsible->setStatus(VisitorEventStatusEnum::Jumped);
        } else {
            $this->updateCacheContract($responsible, $jump->value);

            $responsible->setStatus(VisitorEventStatusEnum::JumpedToChain);
        }
    }

    private function updateCacheContract(Responsible $responsible, string $jumpValue): void
    {
        $contract = $responsible->getEvent()->getContract();
        $flag = true;

        $this->updateCacheChains($contract->getChains(), $jumpValue, $flag);
    }

    private function updateCacheChains(array $chains, string $jumpValue, bool &$flag): void
    {
        /** @var CacheChainDto $chain */
        foreach ($chains as $chain) {
            if ($chain->getTarget()->value === $jumpValue) {
                $chain->setRepeat(true);
                $flag = false;
            }

            $chain->setFinished($flag);
        }
    }
}
