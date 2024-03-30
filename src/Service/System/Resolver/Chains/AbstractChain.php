<?php

namespace App\Service\System\Resolver\Chains;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\GotoChainsEnum;
use App\Enum\GotoScenarioEnum;
use App\Enum\NavigateEnum;
use App\Service\System\Contract;
use App\Service\System\Resolver\Chains\Dto\ConditionInterface;
use App\Service\System\Resolver\Chains\Dto\ContractInterface;

abstract class AbstractChain
{
    abstract public function success(Contract $contract, CacheDto $cacheDto): ContractInterface;

    abstract public function fail(Contract $contract, CacheDto $cacheDto): ContractInterface;

    abstract public function validate(string $content): bool;

    abstract public function condition(): ConditionInterface;

    public function chain(Contract $contract, CacheDto $cacheDto, ?AbstractChain $nextChain = null): ContractInterface
    {

        if ($cacheDto->getEvent()->getCurrentChain()->isRepeat()) {
            $this->success($contract, $cacheDto);

            return $contract;
        }

        if ($this->gotoIsNavigate($cacheDto->getContent(), $contract)) {
            return $contract;
        }

        if ($this->validate($cacheDto->getContent())) {
            $this->success($contract, $cacheDto);

            $nextCondition = $nextChain->condition(); // todo собираем всё что нужно для нового чейна

            return $contract;
        }

        $this->fail($contract, $cacheDto);

        return $contract;
    }

    private function gotoIsNavigate(string $content, Contract $contract): bool
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
