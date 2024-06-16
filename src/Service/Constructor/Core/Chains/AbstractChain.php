<?php

namespace App\Service\Constructor\Core\Chains;

use App\Helper\JumpHelper;
use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Dto\Condition;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

/**
 * Должен быть функционал который валидирует (врзмодно, он далее предаёт какие-то валидные данные)
 * Должен быть функционал который исполняет какую-то работу (рутины, бытовуха)
 * Должен быть функционал который завершает выполнение цепи.
 * Должен быть функционал который предоставляет подготовленное состояние соедующей цепи если такое есть
 * Должен быть функционал который говорит что делать если у нас цепь зафейлена
 * Должен быть фенкционал который говорит что цепь не завершена, но не зафейлена, скорее не закончена и нужно повторить действие
 *
 * при всех равных на состояние следующей цепи влияет результат той на который сейчас находимся
 */
abstract class AbstractChain implements ChainInterface
{
    abstract public function complete(ResponsibleInterface $responsible): ResponsibleInterface; // complete

    /**
     * Решает, валидно ли значение, производит доп махинации, может содеражить логику.
     */
    abstract public function perform(ResponsibleInterface $responsible): bool; // perform - исполнитель

    abstract public function validate(ResponsibleInterface $responsible): bool; // валидируем

//    abstract public function repeat(ResponsibleInterface $responsible): bool; // повторитель

    abstract public function condition(ResponsibleInterface $responsible): ConditionInterface;

    public function execute(ResponsibleInterface $responsible, ?ChainInterface $nextChain): bool
    {
        if ($responsible->getChain()->isRepeat()) {
            $this->complete($responsible);

            $responsible->getChain()->setFinished(true);

            return true;
        }

        if ($this->gotoIsNavigate($responsible)) {
            return true;
        }

        if (!$this->validate($responsible)) {
            $this->fail($responsible);

            return false;
        }

        if (!$this->perform($responsible)) {
            return false;
        }

        $this->complete($responsible);

        $responsible->getChain()->setFinished(true);

        if (null !== $nextChain) {
            $keyBoard = $nextChain->condition($responsible)->getKeyBoard();

            $message = $responsible->getResult()->getMessage();

            $message->setKeyBoard($keyBoard);
        }

        return true;
    }

    public function fail(ResponsibleInterface $responsible): ResponsibleInterface
    {
        if ($responsible->getResult()->isEmptyMessage()) {
            $message = "Не понимаю что вы от меня хотите, повторите выбор:";
            $keyBoard = $this->condition($responsible)->getKeyBoard();

            $responsibleMessage = MessageHelper::createResponsibleMessage(
                message: $message,
                keyBoard: $keyBoard,
            );

            $responsible->getResult()->setMessage($responsibleMessage);
        }

        return $responsible;
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
}
