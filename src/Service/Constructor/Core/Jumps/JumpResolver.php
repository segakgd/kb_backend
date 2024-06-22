<?php

namespace App\Service\Constructor\Core\Jumps;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Enum\TargetEnum;
use App\Enum\VisitorEventStatusEnum;
use App\Helper\CacheHelper;
use App\Service\Constructor\Core\Dto\Responsible;
use App\Service\DtoRepository\ResponsibleDtoRepository;
use App\Service\Visitor\Scenario\ScenarioService;
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

        $scenario = $this->resolveScenario($responsible->getJump());

        if ($scenario) {
            $visitorEvent->setScenarioUUID($scenario->getUUID());

            $responsible->getCacheDto()->setEvent(CacheHelper::createCacheEventDto());
            $responsible->getCacheDto()->setEventUUID($scenario->getUUID());

            $this->responsibleDtoRepository->save($visitorEvent, $responsible);
        } else {
            $this->updateCacheContract($responsible->getCacheDto(), $jump->value);
        }

        $responsible->setStatus(VisitorEventStatusEnum::Repeat);
    }

    private function resolveScenario(TargetEnum $jumpValue): ?Scenario
    {
        return match ($jumpValue) {
            TargetEnum::Main => $this->scenarioService->getMainScenario(),
            TargetEnum::Cart => $this->scenarioService->getCartScenario(),
            default          => null,
        };
    }

    private function updateCacheContract(CacheDto $cacheDto, string $jumpValue): void
    {
        $contract = $cacheDto->getEvent()->getContract();
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
