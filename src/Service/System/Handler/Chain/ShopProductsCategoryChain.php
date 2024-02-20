<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\Core\Telegram\Request\Message\MessageDto;

class ShopProductsCategoryChain
{
    public function handle(MessageDto $messageDto, ?string $content = null): bool
    {
        if ($this->checkCondition($content)) {
            $messageDto->setText('Вы выбрали категорию ' . $content . 'отличный выбор! В теперь давайте выберим товар:');

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

            $messageDto->setReplyMarkup($replyMarkups);

            return true;
        }

        if ($this->checkSystemCondition($content)) {
            $messageDto->setText('Давайте представим что вы вернулись в главное меню');

            return false;
        }

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

        $messageDto->setText('Не понимаю что вы от меня хотите, повторите...');
        $messageDto->setReplyMarkup($replyMarkups);

        return false;
    }

    private function checkCondition(string $content): bool
    {
        $awaitsForNextChain = [
            'магнитолы',
            'динамики',
        ];

        if (in_array($content, $awaitsForNextChain)) {
            return true;
        }

        return false;
    }

    private function checkSystemCondition(string $content): bool
    {
        $awaitsSystem = [
            'вернуться в главное меню',
        ];

        if (in_array($content, $awaitsSystem)) {
            return true;
        }

        return false;
    }
}
