<?php

namespace App\Service\System\Resolver\Chains;

use App\Enum\JumpEnum;
use App\Helper\ChainsGeneratorHelper;
use App\Service\System\Resolver\Chains\Items\AbstractChain;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\Contract;

class ChainResolver
{
    public function resolve(Contract $contract, ?JumpEnum $targetNext): void
    {
        $chainInstance = $this->getChainInstance($contract);
        $nextCondition = $this->getNextCondition($targetNext);

        $contract->setNextCondition($nextCondition);

        $isHandled = $chainInstance->chain($contract);

        if ($isHandled) {
            $contract->getChain()->setFinished(true);
        }
    }

    private function getChainInstance(Contract $contract): AbstractChain
    {
        return ChainsGeneratorHelper::generate($contract->getChain()->getTarget());
    }

    private function getNextCondition(?JumpEnum $targetNext): ?ConditionInterface
    {
        if (is_null($targetNext)) {
            return null;
        }

        return ChainsGeneratorHelper::generate($targetNext)->condition();
    }
}
