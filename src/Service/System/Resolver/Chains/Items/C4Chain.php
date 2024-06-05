<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Enum\JumpEnum;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Condition;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ResponsibleInterface;

class C4Chain extends AbstractChain
{
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();

        $message = "Это шаг 1 элемент цепочки C4. \n\n Вы кликнули на $content";

//        if ($content === 'Да') {
//            $responsible->setJump(JumpEnum::refChain1);
//
//            return $responsible;
//        }

        $message = "Вы кликнули на $content";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
            keyBoard: $responsible->getNextCondition()->getKeyBoard()
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Да 4'
                ],
                [
                    'text' => 'Нет 4'
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
            'Да 4',
            'Нет 4',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}
