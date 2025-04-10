<?php

namespace App\Service\Constructor\Actions\Cart;

use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Actions\AbstractAction;
use App\Service\Constructor\Core\Dto\ConditionInterface;
use App\Service\Constructor\Core\Dto\ResponsibleInterface;

class CartStartAction extends AbstractAction
{
    public static function getName(): string
    {
        return 'cart.start.action';
    }

    public function complete(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $totalAmount = $responsible->getCart()->getTotalAmount();

        $message = "Сумма товаров в корзине: $totalAmount";

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
