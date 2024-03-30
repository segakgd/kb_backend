<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Service\System\Resolver\Chains\AbstractChain;
use App\Service\System\Resolver\Chains\Dto\Condition;
use App\Service\System\Resolver\Chains\Dto\ConditionInterface;
use App\Service\System\Resolver\Chains\Dto\Contract;
use App\Service\System\Resolver\Chains\Dto\ContractInterface;

class C4Chain extends AbstractChain
{
    public function success(ContractInterface $contract, string $content): ContractInterface
    {
        return new Contract();
    }

    public function fail(ContractInterface $contract, string $content): ContractInterface
    {
        return new Contract();
    }

    public function condition(): ConditionInterface
    {
        return new Condition();
    }

    public function validate(string $content): bool
    {
        return true;
    }
}
