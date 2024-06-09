<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\Responsible;
use App\Service\System\Core\Dto\ResponsibleInterface;

class ShippingApartmentChain  extends AbstractChain
{
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $cacheDto = $responsible->getCacheDto();
        $content = $cacheDto->getContent();

        $shipping = $cacheDto->getCart()->getShipping();

        $shipping['address']['apartment'] = $content;

        $cacheDto->getCart()->setShipping($shipping);

        $message = "Ваши апартаменты $content. \n\n Хотите что-то изменить?";

        $replyMarkups = [
            [
                [
                    'text' => 'Изменить контакты'
                ],
                [
                    'text' => 'Изменить доставку'
                ],
                [
                    'text' => 'Изменить продукты'
                ],
            ],
            [
                [
                    'text' => 'Продолжить',
                ],
            ],
            [
//                [
//                    'text' => 'Удалить заказ'
//                ],
//                [
//                    'text' => 'Оплатить'
//                ],
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            $message,
            null,
            $replyMarkups,
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(): ConditionInterface
    {
        $replyMarkups = [
            [
                [
                    'text' => 'Поставить состояние для ' . static::class
                ],
            ],
        ];

        $condition = new Condition();

        $condition->setKeyBoard($replyMarkups);

        return $condition;
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}
