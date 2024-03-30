<?php

namespace App\Service\System\Resolver\Chains;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\GotoChainsEnum;
use App\Enum\GotoScenarioEnum;
use App\Enum\NavigateEnum;
use App\Service\System\Resolver\Chains\Dto\ConditionInterface;
use App\Service\System\Resolver\Chains\Dto\ContractInterface;

abstract class AbstractChain
{
    public function chain(
        ContractInterface $contract,
        CacheDto $cacheDto,
        ?AbstractChain $nextChain = null,
    ): ContractInterface {
        // todo мб стоит расширить роль контракта... что если он будет ещё помнить о чейнах? Аля чтоб не прокидывать везде кеш, писать туда, а потом это мапить. так мы ученьшим связанность эл-в

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

    abstract public function success(ContractInterface $contract, CacheDto $cacheDto): ContractInterface;

    private function gotoIsNavigate(string $content, ContractInterface $contract): bool
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

    abstract public function validate(string $content): bool;

    abstract public function condition(): ConditionInterface;

    abstract public function fail(ContractInterface $contract, CacheDto $cacheDto): ContractInterface;
}
