<?php

namespace App\Service\System\Resolver\Chains;

use App\Enum\JumpEnum;
use App\Helper\ChainsGeneratorHelper;
use App\Service\System\Resolver\Dto\Contract;

class ChainResolver
{
    public function resolve(Contract $contract, ?JumpEnum $targetNext): void
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
