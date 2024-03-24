<?php

namespace App\Service\System\Handler\Chain\Items\Cart\Shipping;

use App\Controller\Admin\Lead\DTO\Response\Order\Shipping\ShippingRespDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;

class ShippingChain extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        $replyMarkups = [
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $message = 'Не понимаю о чём вы';

        $cart = $cacheDto->getCart();

        if ($content === 'Указать адрес доставки') {
            $message = 'Выберите город, или введите его вручную';

            $replyMarkups[] = [
                [
                    'text' => 'Москва'
                ],
                [
                    'text' => 'Калининград'
                ],
            ];

            // todo вывести города.

            $shipping = [
                'type' => ShippingRespDto::TYPE_COURIER,
                'address' => [
                    'country' => null, // cart.shipping.country
                    'region' => null, // cart.shipping.region
                    'city' => null, // cart.shipping.city
                    'street' => null, // cart.shipping.street
                    'numberHome' => null, // cart.shipping.numberHome
                    'entrance' => null, // cart.shipping.entrance
                    'apartment' => null, //  cart.shipping.apartment
                ]
            ];

            $cart->setShipping($shipping);
        }

        if ($content === 'Самовывоз') {
            $message = 'Хорошо, так и укажем';

            $shipping = [
                'type' => ShippingRespDto::TYPE_PICKUP,
                'address' => ''
            ];

            $cart->setShipping($shipping);
            $contract->setGoto('cart.finish');
        }

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
