<?php

namespace App\Service\Constructor\Items;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class StartChain extends AbstractChain
{
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $message = "Выберите интересующую вас категорию товаров: ";

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
                        'text' => 'Погнали!'
                    ],
                ],
            ]
        );
    }

    public function perform(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return $this->isValid(
            $responsible,
            [
                'Погнали!',
            ]
        );
    }
}
