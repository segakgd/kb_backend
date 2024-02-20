<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\Core\Telegram\Request\Message\MessageDto;

class ShowShopProductsCategoryChain
{
    public function handle(MessageDto $messageDto, ?string $content = null): bool
    {
        $replyMarkups = [
            [
                [
                    'text' => 'магнитолы'
                ],
                [
                    'text' => 'динамики'
                ],
            ],
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
            ],
        ];

        $messageDto->setText('Отлично, 😜 выберите одну из категорий 🤘');
        $messageDto->setReplyMarkup($replyMarkups);

        return true;
    }
}
