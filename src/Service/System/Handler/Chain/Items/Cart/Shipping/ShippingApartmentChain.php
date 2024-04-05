<?php

namespace App\Service\System\Handler\Chain\Items\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;

class ShippingApartmentChain extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
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

        $contractMessage = MessageHelper::createContractMessage(
            $message,
            null,
            $replyMarkups,
        );

        $contract->addMessage($contractMessage);

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}
