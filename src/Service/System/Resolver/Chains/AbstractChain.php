<?php

namespace App\Service\System\Resolver\Chains;

use App\Enum\GotoChainsEnum;
use App\Enum\GotoScenarioEnum;
use App\Enum\NavigateEnum;
use App\Service\System\Contract;
use App\Service\System\Resolver\Chains\Dto\ConditionInterface;
use App\Service\System\Resolver\Chains\Dto\ContractInterface;

abstract class AbstractChain
{
    abstract public function validate(): bool;

    abstract public function condition(): ConditionInterface;

    abstract public function success(ConditionInterface $nextCondition): ContractInterface;

    abstract public function fail(): ContractInterface;

    public function chain(): bool
    {
        return true;
    }

    public function gotoIsNavigate(string $content, Contract $contract): bool
    {
        $result = match ($content) {
            NavigateEnum::ToMain->value => GotoScenarioEnum::Main->value,
            NavigateEnum::ToCart->value => GotoScenarioEnum::Cart->value,
            NavigateEnum::ToProducts->value => GotoChainsEnum::ShopProducts->value,
            NavigateEnum::ToCategoryProducts->value => GotoChainsEnum::ShowShopProductsCategory->value,
            default => null
        };

        if ($result) {
            $contract->setGoto($result);

            return true;
        }

        return false;
    }
}
