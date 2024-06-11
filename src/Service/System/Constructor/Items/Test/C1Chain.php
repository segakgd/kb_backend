<?php

namespace App\Service\System\Constructor\Items\Test;

use App\Helper\MessageHelper;
use App\Service\System\Constructor\Core\Chains\AbstractChain;
use App\Service\System\Constructor\Core\Dto\Condition;
use App\Service\System\Constructor\Core\Dto\ConditionInterface;
use App\Service\System\Constructor\Core\Dto\ResponsibleInterface;

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
