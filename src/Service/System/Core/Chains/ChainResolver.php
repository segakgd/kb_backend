<?php

namespace App\Service\System\Core\Chains;

use App\Enum\JumpEnum;
use App\Helper\ChainsGeneratorHelper;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\Responsible;

class ChainResolver
{
    public function resolve(Responsible $responsible, ?JumpEnum $targetNext): void
    {
        $chainInstance = $this->getChainInstance($responsible);
        $nextCondition = $this->getNextCondition($targetNext);

        $responsible->setNextCondition($nextCondition);

        $isHandled = $chainInstance->chain($responsible);

        if ($isHandled) {
            $responsible->getChain()->setFinished(true);
        }
    }

    private function getChainInstance(Responsible $responsible): AbstractChain
    {
        return ChainsGeneratorHelper::generate($responsible->getChain()->getTarget());
    }

    private function getNextCondition(?JumpEnum $targetNext): ?ConditionInterface
    {
        if (is_null($targetNext)) {
            return null;
        }

        return ChainsGeneratorHelper::generate($targetNext)->condition();
    }
}
