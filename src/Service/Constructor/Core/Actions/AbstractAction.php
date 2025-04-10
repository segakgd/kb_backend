<?php

namespace App\Service\Constructor\Core\Actions;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

abstract class AbstractAction implements ActionInterface
{
    use ActionUtilsTrait;

    abstract public static function getName(): string;

    /**
     * Единица, которая выполняется перед
     */
    abstract public function before(ResponsibleInterface $responsible): bool;

    /**
     * Основное действие
     */
    abstract public function complete(ResponsibleInterface $responsible): ResponsibleInterface;

    /**
     * Единица, которая выполняется перед
     */
    abstract public function after(ResponsibleInterface $responsible): bool;

    /**
     * Валидация того, что пришло с внешнего мира
     */
    abstract public function validate(ResponsibleInterface $responsible): bool;

    /**
     * Условие которое нужно для прохождения данного чейна
     */
    abstract public function condition(ResponsibleInterface $responsible): ConditionInterface;

    /**
     * Точка входа
     */
    public function execute(ResponsibleInterface $responsible, ?ActionInterface $nextAction): bool
    {
        if (!$responsible->getAction()->isRepeat()) {
            if (!$this->validate($responsible)) {
                $this->fail($responsible);

                return false;
            }
        }

        return $this->performOrComplete($responsible, $nextAction);
    }

    /**
     * Чейн не прошёл (к примеру валидация)
     */
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

    private function performOrComplete(ResponsibleInterface $responsible, ?ActionInterface $nextAction): bool
    {
        $perform = $this->before($responsible);

        if (!$perform) {
            return false;
        }

        $this->complete($responsible);

        $responsible->getAction()->setFinished(true);

        $nextActionKeyBoard = $nextAction?->condition($responsible)->getKeyBoard();

        if (null !== $nextActionKeyBoard) {
            $message = $responsible->getResult()->getMessage();

            $message->setKeyBoard($nextActionKeyBoard);
        }

        $this->after($responsible);

        return true;
    }
}
