<?php

namespace App\Helper;

use App\Dto\Common\KeyboardDto;
use App\Entity\Ecommerce\ProductVariant;
use Doctrine\Common\Collections\Collection;

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

    public static function getProductCategoryNav(array $availableCategories): array
    {
        $navCategory = [];

        foreach ($availableCategories as $availableCategory) {
            $navCategory[] = [
                'text' => $availableCategory,
            ];
        }

        return [
            $navCategory,
            [
                [
                    'text' => 'вернуться в главное меню',
                ],
            ],
        ];
    }

    public static function getAvailableProductNavItems(): array
    {
        return [
            'предыдущий',
            'добавить в корзину',
            'следующий',
        ];
    }

    public static function getVariantsNav(Collection $variants): array
    {
        $nav = [];

        /** @var ProductVariant $variant */
        foreach ($variants as $variant) {
            $nav[] = [
                [
                    'text' => $variant->getName(),
                ],
            ];
        }

        $nav[] = [
            [
                'text' => 'вернуться в главное меню',
            ],
            [
                'text' => 'вернуться к товарам',
            ],
        ];

        return $nav;
    }

    public static function getProductNav(?array $paginate = null): array
    {
        $nav = [
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

        if (is_array($paginate) && !isset($paginate['prev'])) {
            $nav = [
                [
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

        if (is_array($paginate) && !isset($paginate['next'])) {
            $nav = [
                [
                    [
                        'text' => 'Предыдущий',
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

        return $nav;
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
