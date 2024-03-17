<?php

namespace App\Service\System\Handler\Step;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Common\CacheService;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\ChainsHandler;
use App\Service\System\Handler\Scenario\ScenarioHandler;
use Exception;
use Throwable;

class StepHandler
{
    public function __construct(
        private readonly ChainsHandler $chainHandler,
        private readonly ScenarioHandler $scenarioHandler,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(
        Contract $contract,
        CacheDto $cacheDto,
        array $step,
    ): Contract {
        try {
            if (!empty($step['chain'])) {
                if (!$cacheDto->getEvent()->isExistChains()) {
                    CacheService::enrichStepCache($step['chain'], $cacheDto);
                }

                $contract = $this->chainHandler->handle($contract, $cacheDto);

            } else {
                $contract = $this->scenarioHandler->handle($contract, $step);
                $cacheDto->getEvent()->setFinished(true);
            }
        } catch (Throwable $exception) {
            dd($exception);
        }

        return $contract;
    }
}
