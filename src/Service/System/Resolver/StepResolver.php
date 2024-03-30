<?php

namespace App\Service\System\Resolver;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Common\CacheService;
use App\Service\System\Contract;
use Exception;
use Throwable;

class StepResolver
{
    public function __construct(
        private readonly ChainResolver $chainResolver,
        private readonly ScenarioResolver $scenarioResolver,
    ) {
    }

    /**
     * @throws Exception
     */
    public function resolve(array $steps, Contract $contract, CacheDto $cacheDto): void
    {
        try {
            foreach ($steps as $step) {
                $chains = $step['chain'] ?? [];

                if (!empty($chains)) {
                    $this->chain($contract, $cacheDto, $step);
                } else {
                    $this->scenario($contract, $cacheDto, $step);
                }
            }
        } catch (Throwable $exception) {
            dd($exception);
        }
    }

    /**
     * @throws Exception
     */
    private function chain(Contract $contract, CacheDto $cacheDto, array $step): void
    {
        if ($cacheDto->getEvent()->isEmptyChains()) {
            CacheService::enrichStepCache($step['chain'], $cacheDto);
        }

        $this->chainResolver->resolve($contract, $cacheDto);
    }

    private function scenario(Contract $contract, CacheDto $cacheDto, array $step): void
    {
        $this->scenarioResolver->resolve($contract, $step);

        $cacheDto->getEvent()->setFinished(true);
    }
}
