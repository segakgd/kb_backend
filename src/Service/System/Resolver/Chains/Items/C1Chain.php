<?php

namespace App\Service\System\Resolver\Chains\Items;

use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Condition;
use App\Service\System\Resolver\Dto\ConditionInterface;
use App\Service\System\Resolver\Dto\ContractInterface;

class C1Chain extends AbstractChain
{
    public function success(ContractInterface $contract): ContractInterface
    {
        $content = $contract->getCacheDto()->getContent();

//        $cart = $contract->getCacheDto()->getCart();
//
//        $shipping = [
//            'address' => [ // todo need realization as DTO
//                'apartment' => $content
//            ]
//        ];
//
//        $cart->setShipping($shipping);

        $message = "Это шаг 1 элемент цепочки C1. \n\n Сама цепочка была запущена с помощью конпки $content. \n\n Хотите что-то изменить?";

        $contractMessage = MessageHelper::createContractMessage(
            message: $message,
            keyBoard: $contract->getNextCondition()->getKeyBoard()
        );

        $contract->getResult()->addMessage($contractMessage);

        return $contract;
    }

    public function condition(): ConditionInterface
    {
        return new Condition();
    }

    public function validate(ContractInterface $contract): bool
    {
        return true;
    }
}
