<?php

namespace App\Service\System\Handler\Chain\Items\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;

class ShippingStreetChain extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        $shipping = $cacheDto->getCart()->getShipping();

        $shipping['address']['street'] = $content;

        $cacheDto->getCart()->setShipping($shipping);

        $message = "Ваша улица $content. Введите номер дома:";

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
        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}
