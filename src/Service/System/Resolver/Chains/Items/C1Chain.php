<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Condition;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ResponsibleInterface;

class C1Chain extends AbstractChain
{
    public function success(ResponsibleInterface $responsible): ResponsibleInterface
    {
        $content = $responsible->getCacheDto()->getContent();

//        $cart = $responsible->getCacheDto()->getCart();
//
//        $shipping = [
//            'address' => [ // todo need realization as DTO
//                'apartment' => $content
//            ]
//        ];
//
//        $cart->setShipping($shipping);

        $message = "Это шаг 1 элемент цепочки C1. \n\n Сама цепочка была запущена с помощью конпки $content. \n\n Хотите что-то изменить?";

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            message: $message,
            keyBoard: $responsible->getNextCondition()->getKeyBoard()
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return $responsible;
    }

    public function condition(): ConditionInterface
    {
        return new Condition();
    }

    public function validate(ResponsibleInterface $responsible): bool
    {
        return true;
    }
}
