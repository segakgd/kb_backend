<?php

namespace App\Service\System\Resolver\Chains;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Enum\JumpEnum;
use App\Helper\ChainsGeneratorHelper;
use App\Service\System\Resolver\Dto\Contract;
use Exception;

class ChainResolver
{
    /**
     * @param array<CacheChainDto> $chains
     *
     * @throws Exception
     */
    public function resolve(Contract $contract, array $chains): array
    {
        /** @var CacheChainDto $chain */
        foreach ($chains as $key => $chain) {
            $nextChain = $chains[1 + $key] ?? null;

            $contract->setChain($chain);

            $this->handleChain($contract, $nextChain?->getTarget());

            if ($contract->getJump() !== null) {
                break;
            }

            if ($chain->isFinished()) {
                unset($chains[$key]);
            }

            break;
        }

        return $chains;
    }

    /**
     * @throws Exception
     */
    private function handleChain(Contract $contract, ?JumpEnum $targetNext): void
    {
        $chain = ChainsGeneratorHelper::generate($contract->getChain()->getTarget());

        if (!is_null($targetNext)) {
            $condition = ChainsGeneratorHelper::generate($targetNext)->condition();

            $contract->setNextCondition($condition);
        }

        $isHandled = $chain->chain($contract);

        if ($isHandled) {
            $contract->getChain()->setFinished(true);
        }
    }
}
