<?php

namespace App\Service\System\Handler\Items\Sub;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Entity\Scenario\Scenario;
use App\Entity\Visitor\VisitorSession;
use App\Service\System\Handler\Contract;
use Exception;

class StepHandler
{
    public function __construct(
        private readonly ChainHandler $chainHandler,
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
        string $scenarioUUID,
    ): Contract {

        if ($step['chain']) {
            dd('chain');
        }

        if (isset($step['chain']) || $cacheDto->getEvent()->getChains()) {
            $contract = $this->chainHandler->handle($contract, $cacheDto);

        } else {
            $contract = $this->scenarioHandler->handle($contract, $step);
        }

        return $contract;
    }
}
