<?php

namespace App\Service\System\Core\Chains\Items\Ecommerce\Cart\Shipping;

use App\Controller\Admin\Lead\DTO\Response\Order\Shipping\ShippingRespDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Dto\Condition;
use App\Service\System\Core\Dto\ConditionInterface;
use App\Service\System\Core\Dto\Responsible;
use App\Service\System\Core\Dto\ResponsibleInterface;

class ShippingChain  extends AbstractChain
{
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
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
            $message = 'Выберите свою страну';

            $replyMarkups[] = [
                [
                    'text' => 'Россия'
                ],
                [
                    'text' => 'Беларусь'
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
//            $responsible->setGoto('cart.finish');
        }

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
        // TODO: Implement validate() method.
    }
}
