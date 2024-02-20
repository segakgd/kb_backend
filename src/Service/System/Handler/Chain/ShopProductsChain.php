<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\Core\Telegram\Request\Message\MessageDto;

class ShopProductsChain
{
    public function handle(MessageDto $messageDto, ?string $content = null): bool
    {
        $replyMarkups = [
            [
                [
                    'text' => 'предыдущий'
                ],
                [
                    'text' => 'подробнее о товаре'
                ],
                [
                    'text' => 'следующий'
                ],
            ],
            [
                [
                    'text' => 'добавить в корзину'
                ],
                [
                    'text' => 'вернуться в главное меню'
                ],
            ],
        ];

        $messageDto->setText('Вы выбрали ' . $content . ' отличный выбор, давайте подробнее рассмотрим');
        $messageDto->setReplyMarkup($replyMarkups);

        return false;
    }
}
