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
        $chainCount = count($chains);

        foreach ($chains as $key => $chain) {
            $nextChain = $chains[1 + $key] ?? null;

            $contract->setChain($chain);

            $this->handleChain($nextChain?->getTarget(), $contract);

            if ($contract->getJump() !== null) {
                break;
            }

            if ($chain->isFinished() && $key === $chainCount - 1) {
                $contract->getChain()->setFinished(true);

                unset($chains[$key]);
            }

            break;
        }

        return $chains;
    }

    /**
     * @throws Exception
     */
    private function handleChain(JumpEnum $targetNext, Contract $contract): void
    {
        $chain = ChainsGeneratorHelper::generate($contract->getChain()->getTarget());
        $condition = ChainsGeneratorHelper::generate($targetNext)->condition();

        $contract->setNextCondition($condition);

        $isHandled = $chain->chain($contract);

        if ($isHandled) {
            $contract->getChain()->setFinished(true);
        }
    }
}
