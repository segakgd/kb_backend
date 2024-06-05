<?php

namespace App\Service\System\Resolver\Chains\Items\Items\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Responsible;

class ShippingApartmentChain //  extends AbstractChain
{
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
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

        return true;
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}
