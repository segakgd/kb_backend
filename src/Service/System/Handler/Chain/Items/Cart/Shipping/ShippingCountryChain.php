<?php

namespace App\Service\System\Handler\Chain\Items\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;

class ShippingCountryChain extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        $shipping = $cacheDto->getCart()->getShipping();

        $shipping['address']['country'] = $content;

        $cacheDto->getCart()->setShipping($shipping);

        $message = "Ваши страна $content. Введите свой регион:";

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

        $contract->addMessage($contractMessage);

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        // todo проверить доступные города

        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}
