<?php

namespace App\Service\System\Resolver\Chains\Items\Items\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Resolver\Dto\Contract;

class ShippingRegionChain // extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        $shipping = $cacheDto->getCart()->getShipping();

        $shipping['address']['region'] = $content;

        $cacheDto->getCart()->setShipping($shipping);

        $message = "Вах регион $content. Введите город:";

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
