<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\JumpHelper;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ResponsibleInterface;

abstract class AbstractChain
{
    public function chain(ResponsibleInterface $responsible): bool
    {
        // todo если используем как стек, то не нужно будет проверять на повтор
        if ($responsible->getChain()->isRepeat()) {
            $this->success($responsible);

            $responsible->getChain()->setFinished(true);

            return true;
        }

        if ($this->gotoIsNavigate($responsible)) {
            return true;
        }

        if ($this->validate($responsible)) {
            $this->success($responsible);

            $responsible->getChain()->setFinished(true);

            return true;
        }

        $this->fail($responsible);

        return false;
    }

    abstract public function success(ResponsibleInterface $responsible): ResponsibleInterface;

    private function gotoIsNavigate(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getCacheDto()->getContent();

        $jump = JumpHelper::getJumpFromNavigate($content);

        if ($jump) {
            $responsible->setJump($jump);

            return true;
        }

        return false;
    }

    abstract public function validate(ResponsibleInterface $responsible): bool;

    public function fail(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $message = "Не понимаю что вы от меня хотите, повторите выбор:";
        $keyBoard = $this->condition()->getKeyBoard();

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
            keyBoard: $keyBoard,
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return $responsible;
    }

    abstract public function condition(): ConditionInterface;
}
