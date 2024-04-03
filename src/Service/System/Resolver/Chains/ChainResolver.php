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
            if ($chain->isFinished()) {
                continue;
            }

            $this->handleChain($chain->getTarget(), $contract);

            if ($contract->getJump() !== null) {
                break;
            }

            if ($chain->isFinished() && $key === $chainCount - 1) {
                $contract->getChain()->setFinished(true);
            }

            break;
        }

        return $chains;
    }

    /**
     * @throws Exception
     */
    private function handleChain(JumpEnum $target, Contract $contract): void
    {
        $chain = ChainsGeneratorHelper::generate($target);

        // todo вот тут по сути должно быть состояние того чейна, который бкдет следующий.
        $condition = ChainsGeneratorHelper::generate($target)->condition();

        $contract->setNextCondition($condition);

        $isHandled = $chain->chain($contract);

        if ($isHandled) {
            $contract->getChain()->setFinished(true);
        }
    }
}
