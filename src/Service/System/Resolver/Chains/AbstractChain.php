<?php

namespace App\Service\System\Resolver\Chains;

use App\Enum\JumpEnum;
use App\Enum\NavigateEnum;
use App\Helper\JumpHelper;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

abstract class AbstractChain
{
    abstract public function success(ContractInterface $contract): ContractInterface;

    abstract public function validate(ContractInterface $contract): bool;

    abstract public function condition(): ConditionInterface;

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

    private function gotoIsNavigate(ContractInterface $contract): bool
    {
        $content = $contract->getContent();

        $jump = JumpHelper::getJumpFromNavigate($content);

        if ($jump) {
            $contract->setJump($jump);

            return true;
        }

        return false;
    }

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
}
