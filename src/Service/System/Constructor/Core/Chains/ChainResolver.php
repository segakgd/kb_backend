<?php

namespace App\Service\System\Constructor\Core\Chains;

use App\Enum\JumpEnum;
use App\Helper\ChainsGenerator;
use App\Service\System\Constructor\Core\Dto\ConditionInterface;
use App\Service\System\Constructor\Core\Dto\Responsible;
use Exception;

readonly class ChainResolver
{
    public function __construct(private ChainsGenerator $chainsGenerator)
    {
    }

    /**
     * @throws Exception
     */
    public function resolve(Responsible $responsible, ?JumpEnum $targetNext): void
    {
        $chainInstance = $this->getChainInstance($responsible);
        $nextCondition = $this->getNextCondition($targetNext, $responsible);

        $responsible->setNextCondition($nextCondition);

        $isHandled = $chainInstance->chain($responsible);

        if ($isHandled) {
            $responsible->getChain()->setFinished(true);
        }
    }

    /**
     * @throws Exception
     */
    private function getChainInstance(Responsible $responsible): AbstractChain
    {
        return $this->chainsGenerator->generate($responsible->getChain()->getTarget());
    }

    /**
     * @throws Exception
     */
    private function getNextCondition(?JumpEnum $targetNext, Responsible $responsible): ?ConditionInterface
    {
        if (is_null($targetNext)) {
            return null;
        }

        return $this->chainsGenerator->generate($targetNext)->condition($responsible);
    }
}
