<?php

namespace App\Service\Constructor\Core\Chains;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Dto\Condition;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;
use App\Service\Constructor\Core\Helper\JumpHelper;

abstract class AbstractChain implements ChainInterface
{
    abstract public function complete(ResponsibleInterface $responsible): ResponsibleInterface; // complete

    /**
     * Решает, валидно ли значение, производит доп махинации, может содержать логику.
     */
    abstract public function perform(ResponsibleInterface $responsible): bool;

    abstract public function validate(ResponsibleInterface $responsible): bool;

    abstract public function condition(ResponsibleInterface $responsible): ConditionInterface;

    public function execute(ResponsibleInterface $responsible, ?ChainInterface $nextChain): bool
    {
        if (!$responsible->getChain()->isRepeat()) {
            if ($this->isJump($responsible)) {
                // определяем, если сообщение относится к jump-у то завершаем выполнение этого сценария.
                return true;
            }

            if (!$this->validate($responsible)) {
                $this->fail($responsible);

                return false;
            }
        }

        return $this->performOrComplete($responsible, $nextChain);
    }

    public function fail(ResponsibleInterface $responsible): ResponsibleInterface
    {
        if ($responsible->getResult()->isEmptyMessage()) {
            $message = 'Не понимаю что вы от меня хотите, повторите выбор:';
            $keyBoard = $this->condition($responsible)->getKeyBoard();

            $responsibleMessage = MessageHelper::createResponsibleMessage(
                message: $message,
                keyBoard: $keyBoard,
            );

            $responsible->getResult()->setMessage($responsibleMessage);
        }

        return $responsible;
    }

    protected function performOrComplete(ResponsibleInterface $responsible, ?ChainInterface $nextChain): bool
    {
        $perform = $this->perform($responsible);

        if (!$perform) {
            return false;
        }

        $this->complete($responsible);

        $responsible->getChain()->setFinished(true);

        $nextChainKeyBoard = $nextChain?->condition($responsible)->getKeyBoard();

        if (null !== $nextChainKeyBoard) {
            $message = $responsible->getResult()->getMessage();

            $message->setKeyBoard($nextChainKeyBoard);
        }

        return true;
    }

    protected function isValid(ResponsibleInterface $responsible, array $data): bool
    {
        $content = $responsible->getCacheDto()->getContent();

        if (in_array($content, $data)) {
            return true;
        }

        return false;
    }

    protected function makeCondition(array $replyMarkups = []): ConditionInterface
    {
        $condition = new Condition();

        if (!empty($replyMarkups)) {
            $condition->setKeyBoard($replyMarkups);
        }

        return $condition;
    }

    // todo этот метод нужно реализовать в интерфейсе, чтоб можно было дополнять в дочках, и вообще лучше из дочерних и управлять этим. Они и должны решить, jump или нет
    private function isJump(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getCacheDto()->getContent();

        $jump = JumpHelper::getJumpFromNavigate($content);

        if ($jump) {
            $responsible->setJump($jump);

            return true;
        }

        return false;
    }
}
