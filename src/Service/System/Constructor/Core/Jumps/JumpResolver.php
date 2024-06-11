<?php

namespace App\Service\System\Constructor\Core\Jumps;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorEvent;
use App\Enum\VisitorEventStatusEnum;
use App\Service\System\Common\CacheService;
use App\Service\System\Constructor\Core\Dto\Responsible;
use App\Service\Visitor\Scenario\ScenarioService;
use Exception;

readonly class JumpResolver
{
    public function __construct(
        private ScenarioService $scenarioService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function resolveJump(
        VisitorEvent $visitorEvent,
        Responsible  $responsible,
    ): void {
        $jump = $responsible->getJump();

        if (!$jump) {
            throw new Exception('Непредвиденная ситуация: переход не существует.');
        }

        $scenario = $this->resolveScenario($responsible->getJump()->value);

        if ($scenario) {
            $visitorEvent->setScenarioUUID($scenario->getUUID());
            $responsible->getCacheDto()->setEvent(CacheService::createCacheEventDto());
        } else {
            $this->updateCacheContract($responsible->getCacheDto(), $jump->value);
        }

        $responsible->setStatus(VisitorEventStatusEnum::Repeat);
    }

    private function resolveScenario(string $jumpValue): ?Scenario
    {
        return match ($jumpValue) {
            'main' => $this->scenarioService->getMainScenario(),
            'cart' => $this->scenarioService->getCartScenario(),
            default => null,
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
