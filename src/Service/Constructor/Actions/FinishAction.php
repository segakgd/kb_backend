<?php

namespace App\Service\Constructor\Actions;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Actions\AbstractAction;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

/**
 * Финальная заглушка
 */
class FinishAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'finish.chain';
    }

    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $message = 'Это финиш, что бы ты сюда не написал, это не имеет смысла';

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        return $this->makeCondition();
    }

    public function before(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function after(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}
