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
                    'text' => 'вернуться в главное меню'
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
                    'text' => $variant->getName()
                ],
            ];
        }

        $nav[] = [
            [
                'text' => 'вернуться в главное меню'
            ],
            [
                'text' => 'вернуться к товарам'
            ],
        ];

        return $nav;
    }

    public static function getProductNav(?array $paginate = null): array
    {
        $nav = [
            [
                [
                    'text' => 'Предыдущий'
                ],
                [
                    'text' => 'Следующий'
                ],
            ],
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
                [
                    'text' => 'добавить в корзину'
                ],
                [
                    'text' => 'вернуться к категориям'
                ],
            ],
        ];

        if (is_array($paginate) && !isset($paginate['prev'])) {
            $nav = [
                [
                    [
                        'text' => 'Следующий'
                    ],
                ],
                [
                    [
                        'text' => 'вернуться в главное меню'
                    ],
                    [
                        'text' => 'добавить в корзину'
                    ],
                    [
                        'text' => 'вернуться к категориям'
                    ],
                ],
            ];
        }

        if (is_array($paginate) && !isset($paginate['next'])) {
            $nav = [
                [
                    [
                        'text' => 'Предыдущий'
                    ],
                ],
                [
                    [
                        'text' => 'вернуться в главное меню'
                    ],
                    [
                        'text' => 'добавить в корзину'
                    ],
                    [
                        'text' => 'вернуться к категориям'
                    ],
                ],
            ];
        }

        return $nav;
    }
}
