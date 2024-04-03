<?php

namespace App\Service\System\Resolver\Chains;

use App\Enum\JumpEnum;
use App\Enum\NavigateEnum;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

abstract class AbstractChain
{
    public function chain(ContractInterface $contract): bool
    {
        if ($contract->getChain()->isRepeat()) {
            $this->success($contract);

            return true;
        }

        if ($this->gotoIsNavigate($contract)) {
            return true;
        }

        if ($this->validate($contract)) {
            $this->success($contract);

            return true;
        }

        $this->fail($contract);

        return false;
    }

    abstract public function success(ContractInterface $contract): ContractInterface;

    private function gotoIsNavigate(ContractInterface $contract): bool
    {
        $content = $contract->getContent();

        $result = match ($content) {
            NavigateEnum::ToMain->value => JumpEnum::Main,
            NavigateEnum::ToCart->value => JumpEnum::Cart,
            NavigateEnum::ToProducts->value => JumpEnum::ShopProducts,
            NavigateEnum::ToCategoryProducts->value => JumpEnum::ShowShopProductsCategory,
        };

        if ($result) {
            $contract->setJump($result);

            return true;
        }

        return false;
    }

    abstract public function validate(ContractInterface $contract): bool;

    public function fail(ContractInterface $contract): ContractInterface
    {
        $message = "Не понимаю что вы от меня хотите, повторите выбор:";
        $keyBoard = $this->condition()->getKeyBoard();

        $contractMessage = MessageHelper::createContractMessage(
            message: $message,
            keyBoard: $keyBoard,
        );

        $contract->getResult()->addMessage($contractMessage);

        return $contract;
    }

    abstract public function condition(): ConditionInterface;
}
