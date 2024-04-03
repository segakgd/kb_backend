<?php

namespace App\Service\System\Resolver\Chains;

use App\Helper\JumpHelper;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

abstract class AbstractChain
{
    public function chain(ContractInterface $contract): bool
    {
        // todo если используем как стек, то не нужно будет проверять не повтор
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

        $jump = JumpHelper::getJumpFromNavigate($content);

        if ($jump) {
            $contract->setJump($jump);

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
