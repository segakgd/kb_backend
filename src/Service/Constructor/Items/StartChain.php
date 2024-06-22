<?php

namespace App\Service\Constructor\Items;

use App\Enum\TargetEnum;
use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class StartChain extends AbstractChain
{
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $message = 'Выберите интересующую вас категорию товаров: ';

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        return $this->makeCondition(
            [
                [
                    [
                        'text' => 'Погнали!',
                    ],
                ],
                [
                    [
                        'text' => 'Вернуться в главное меню',
                    ],
                ],
            ]
        );
    }

    public function perform(ResponsibleInterface $responsible): bool
    {
        $content = $responsible->getCacheDto()->getContent();

        if ('Вернуться в главное меню' === $content) {
            $responsible->setJump(TargetEnum::Main);

            return false;
        }

        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return $this->isValid(
            $responsible,
            [
                'Погнали!',
                'Вернуться в главное меню',
            ]
        );
    }
}
