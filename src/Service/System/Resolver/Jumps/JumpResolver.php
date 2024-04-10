<?php

namespace App\Service\System\Resolver\Jumps;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Entity\Visitor\VisitorSession;
use App\Enum\ChainStatusEnum;
use App\Enum\VisitorEventStatusEnum;
use App\Service\System\Common\CacheService;
use App\Service\System\Resolver\Dto\Contract;
use App\Service\Visitor\Scenario\ScenarioService;
use Exception;

class JumpResolver
{
    public function __construct(
        private readonly ScenarioService $scenarioService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function resolveJump(
        VisitorEvent $visitorEvent,
        Contract $contract,
    ): void {
        $jump = $contract->getJump();

        if (!$jump) {
            throw new Exception('Непредвиденная ситуация: переход не существует.');
        }

        $scenario = $this->resolveScenario($contract->getJump()->value);

        if ($scenario) {
            $visitorEvent->setScenarioUUID($scenario->getUUID());
            $contract->getCacheDto()->setEvent(CacheService::createCacheEventDto());
        } else {
            $this->updateCacheSteps($contract->getCacheDto(), $jump->value);
        }

        $contract->setStatus(VisitorEventStatusEnum::New);
    }

    private function resolveScenario(string $jumpValue): ?Scenario
    {
        return match ($jumpValue) {
            'main' => $this->scenarioService->getMainScenario(),
            'cart' => $this->scenarioService->getCartScenario(),
            default => null,
        };
    }

    private function updateCacheSteps(CacheDto $cacheDto, string $jumpValue): void
    {
        $steps = $cacheDto->getEvent()->getSteps();

        foreach ($steps as $step) {
            $this->updateCacheChains($step->getChains(), $jumpValue);
        }
    }

    private function updateCacheChains(array $chains, string $jumpValue): void
    {
        $flag = true;

        /** @var CacheChainDto $chain */
        foreach ($chains as $chain) {
            if ($chain->getTarget() === $jumpValue) {
                $chain->setRepeat(true);
                $flag = false;
            }

            $chain->setFinished($flag);
        }
    }
}
