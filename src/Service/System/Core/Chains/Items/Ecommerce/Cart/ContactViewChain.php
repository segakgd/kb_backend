<?php

namespace App\Service\System\Core\Chains\Items\Items\Cart;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\System\Core\Dto\Responsible;

class ContactViewChain // extends AbstractChain
{
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $replyMarkups = [
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ]
        ];

        $responsibleMessage = MessageHelper::createResponsibleMessage(
            'Отлично, давай начнём оформление заказа, пришли мне свое ФИО.',
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
