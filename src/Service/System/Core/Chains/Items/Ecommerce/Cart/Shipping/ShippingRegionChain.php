<?php

namespace App\Service\System\Core\Chains\Items\Items\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Core\Dto\Responsible;

class ShippingRegionChain // extends AbstractChain
{
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
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
}
