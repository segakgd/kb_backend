<?php

namespace App\Service\Constructor\Items\Order;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class OrderShippingChain extends AbstractChain
{
    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();

        $responsible->getCacheDto()->getCart()->setShipping(
            [
                'fullAddress' => $content,
            ]
        );

        $message = "Отлично, адрес доставки $content";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
            keyBoard: [
                [
                    [
                        'text' => 'Оформить заказ',
                    ],
                    [
                        'text' => 'Изменить доставку',
                    ],
                    [
                        'text' => 'Изменить контакты',
                    ],
                ],
                [
                    [
                        'text' => 'Изменить продукты', // todo Изменить заказ
                    ],
                    [
                        'text' => 'Удалить заказ',
                    ],
                    [
                        'text' => 'Оплатить',
                    ],
                ],
                [
                    [
                        'text' => 'В главное меню',
                    ],
                ],
            ]
        );

        $responsible->getResult()->setMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(ResponsibleInterface $responsible): ConditionInterface
    {
        return $this->makeCondition();
    }

    public function perform(ResponsibleInterface $responsible): bool
    {
        return true;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}
