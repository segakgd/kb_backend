<?php

namespace App\Service\System\Constructor\Items\Test;

use App\Helper\MessageHelper;
use App\Service\System\Constructor\Core\Chains\AbstractChain;
use App\Service\System\Constructor\Core\Dto\Condition;
use App\Service\System\Constructor\Core\Dto\ConditionInterface;
use App\Service\System\Constructor\Core\Dto\ResponsibleInterface;

class C9Chain extends AbstractChain
{
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();

        $message = "Это шаг 2 элемент цепочки C9. \n\n Вы кликнули на $content";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
            keyBoard: $responsible->getNextCondition()->getKeyBoard()
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Да 9'
                ],
                [
                    'text' => 'Нет 9'
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getCacheDto()->getContent();

        $validData = [
            'Да 9',
            'Нет 9',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}
