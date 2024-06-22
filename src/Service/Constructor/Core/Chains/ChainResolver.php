<?php

namespace App\Service\Constructor\Core\Chains;

use App\Enum\JumpEnum;
use App\Service\Constructor\ChainsGenerator;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\Responsible;
use Exception;

readonly class ChainResolver
{
    public function __construct(private ChainsGenerator $chainsGenerator) {}

    /**
     * @throws Exception
     */
    public function resolve(Responsible $responsible, ?JumpEnum $targetNext): void
    {
        $chainInstance = $this->getChainInstance($responsible);

        $chainInstance->execute($responsible, $this->getNextChain($targetNext));
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

    /**
     * @throws Exception
     */
    private function getNextChain(?JumpEnum $targetNext): ?AbstractChain
    {
        if (is_null($targetNext)) {
            return null;
        }

        return $this->chainsGenerator->generate($targetNext);
    }
}
