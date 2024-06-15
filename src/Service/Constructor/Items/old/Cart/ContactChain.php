<?php

namespace App\Service\Constructor\Items\old\Cart;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Constructor\Core\Dto\Responsible;

class ContactChain // extends AbstractChain
{
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();
        $contacts = $cacheDto->getCart()->getContacts();

        $contacts['full'] = $content;

        $cacheDto->getCart()->setContacts($contacts);

        $replyMarkups = [
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            "Отлично, $content а теперь пришли мне свой номер телефона",
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
        // todo проверить на стрингу? оО и мат

        return true;
    }
}
