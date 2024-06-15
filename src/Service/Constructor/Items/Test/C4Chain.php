<?php

namespace App\Service\Constructor\Items\Test;

use App\Enum\JumpEnum;
use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\Condition;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class C4Chain extends AbstractChain
{
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();

        $message = "Это шаг 1 элемент цепочки C4. \n\n Вы кликнули на $content";

        if ($content === 'Сделать jump на 2 (4)') {
            $responsible->setJump(JumpEnum::refChain2);

            return $responsible;
        }

        if ($content === 'Сделать jump на 6 (4)') {
            $responsible->setJump(JumpEnum::refChain6);

            return $responsible;
        }


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
                    'text' => 'Сделать jump на 2 (4)'
                ],
                [
                    'text' => 'Сделать jump на 6 (4)'
                ],
                [
                    'text' => 'Продолжить 4'
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
            'Сделать jump на 2 (4)',
            'Сделать jump на 6 (4)',
            'Продолжить (4)',
        ];

        if (in_array($content, $validData)) {
            return true;
        }

        return false;
    }
}
