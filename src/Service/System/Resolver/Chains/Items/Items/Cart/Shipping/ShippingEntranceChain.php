<?php

namespace App\Service\System\Resolver\Chains\Items\Items\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Contract;

class ShippingEntranceChain // extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        $shipping = $cacheDto->getCart()->getShipping();

        $shipping['address']['entrance'] = $content;

        $cacheDto->getCart()->setShipping($shipping);

        $message = "Подъезд № $content. Введите свои апартаменты:";

        $replyMarkups = [
            [
                [
                    'text' => 'Моя корзина'
                ],
            ],
            [
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

        $contract->getResult()->addMessage($contractMessage);

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
