<?php

namespace App\Helper;

use App\Dto\Common\KeyboardDto;

class KeyboardHelper
{
    public static function mapKeyboard(KeyboardDto $keyboardDto): array
    {
        $replyMarkups = [];

        $replyMarkupsFromContract = $keyboardDto->getReplyMarkup();

        foreach ($replyMarkupsFromContract as $key => $replyMarkup) {
            foreach ($replyMarkup as $keyItem => $replyMarkupItem) {
                $replyMarkups[$key][$keyItem]['text'] = $replyMarkupItem['text'];
            }
        }

        return $replyMarkups;
    }

    public static function getProductNav(): array
    {
        return [
            [
                [
                    'text' => 'Предыдущий',
                ],
                [
                    'text' => 'Следующий',
                ],
            ],
            [
                [
                    'text' => 'Вернуться в главное меню',
                ],
                [
                    'text' => 'Добавить в корзину',
                ],
                [
                    'text' => 'Вернуться к категориям',
                ],
            ],
        ];
    }

    public static function getIconNumber(int $number): string
    {
        $numberEmoji = '';

        foreach (str_split($number) as $numberItem) {
            $numberEmoji .= match ((int) $numberItem) {
                0 => '0️⃣',
                1 => '1️⃣',
                2 => '2️⃣',
                3 => '3️⃣',
                4 => '4️⃣',
                5 => '5️⃣',
                6 => '6️⃣',
                7 => '7️⃣',
                8 => '8️⃣',
                9 => '9️⃣',
            };
        }

        return $numberEmoji;
    }
}
