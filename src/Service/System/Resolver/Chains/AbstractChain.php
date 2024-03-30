<?php

namespace App\Service\System\Resolver\Chains;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\GotoChainsEnum;
use App\Enum\GotoScenarioEnum;
use App\Enum\NavigateEnum;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Chains\Dto\ConditionInterface;
use App\Service\System\Resolver\ContractInterface;

abstract class AbstractChain
{
    public function chain(
        ContractInterface $contract,
        CacheDto $cacheDto,
        ?AbstractChain $nextChain = null,
    ): bool {
        // todo мб стоит расширить роль контракта... что если он будет ещё помнить о чейнах?
        //  Аля чтоб не прокидывать везде кеш, писать туда, а потом это мапить. так мы ученьшим связанность эл-в

        if ($cacheDto->getEvent()->getCurrentChain()->isRepeat()) {
            $nextCondition = $nextChain->condition(); // todo собираем всё что нужно для нового чейна

            $this->success($contract, $nextCondition, $cacheDto->getContent());

            return true;
        }

        if ($this->gotoIsNavigate($cacheDto->getContent(), $contract)) {
            return true;
        }

        if ($this->validate($cacheDto->getContent())) {
            $nextCondition = $nextChain->condition(); // todo собираем всё что нужно для нового чейна

            $this->success($contract, $nextCondition, $cacheDto->getContent());

            return true;
        }

        dd(static::class, '');

        $this->fail($contract, $cacheDto->getContent());

        return false;
    }

    abstract public function success(
        ContractInterface $contract,
        ConditionInterface $nextCondition,
        string $content,
    ): ContractInterface;

    abstract public function validate(string $content): bool;

    abstract public function condition(): ConditionInterface;

    public function fail(ContractInterface $contract, string $content): ContractInterface
    {
        $message = "Не понимаю что вы от меня хотите, повторите выбор:";
        $keyBoard = $this->condition()->getKeyBoard();

        $contractMessage = MessageHelper::createContractMessage(
            message: $message,
            keyBoard: $keyBoard,
        );

        $contract->addMessage($contractMessage);

        return $contract;
    }

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
}
