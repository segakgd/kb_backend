<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Service\System\Resolver\Chains\AbstractChain;
use App\Service\System\Resolver\Chains\Dto\Condition;
use App\Service\System\Resolver\Chains\Dto\ConditionInterface;
use App\Service\System\Resolver\Chains\Dto\Contract;
use App\Service\System\Resolver\Chains\Dto\ContractInterface;

class C5Chain extends AbstractChain
{
    public function validate(): bool
    {
        return true;
    }

    public function condition(): ConditionInterface
    {
        return new Condition();
    }

    public function success(ConditionInterface $nextCondition): ContractInterface
    {
        return new Contract();
    }

    public function fail(): ContractInterface
    {
        return new Contract();
    }
}
