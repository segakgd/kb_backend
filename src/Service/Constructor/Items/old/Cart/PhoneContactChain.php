<?php

namespace App\Service\Constructor\Items\old\Cart;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Dto\Responsible;

class PhoneContactChain //extends AbstractChain
{
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();
        $contacts = $cacheDto->getCart()->getContacts();

        $contacts['phone'] = $content;

        $cacheDto->getCart()->setContacts($contacts);

        $replyMarkups = [
            [
                [
                    'text' => 'Указать адрес доставки'
                ],
                [
                    'text' => 'Самовывоз'
                ],
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            "Отлично, ваш номер телефон $content. Нужна ли вам доставка?",
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
        // todo формат телефона

        return true;
    }
}
