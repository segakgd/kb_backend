<?php

namespace App\Helper;

use App\Entity\Ecommerce\ProductVariant;
use Doctrine\Common\Collections\Collection;

class KeyboardHelper
{
    public static function mapKeyboard(array $scenarioStep): array
    {
        $replyMarkups = [];

        foreach ($scenarioStep['keyboard']['replyMarkup'] as $key => $replyMarkup) {
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
            'вернуться в главное меню',
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
                'text' => 'вернуться в к товарам'
            ],
        ];

        return $nav;
    }

    public static function getProductNav(?array $paginate = null): array
    {
        $nav = [
            [
                [
                    'text' => 'предыдущий'
                ],
                [
                    'text' => 'следующий'
                ],
            ],
            [
                [
                    'text' => 'вернуться в главное меню'
                ],
                [
                    'text' => 'добавить в корзину'
                ],
            ],
        ];

        if (is_array($paginate) && !isset($paginate['prev'])) {
            $nav = [
                [
                    [
                        'text' => 'следующий'
                    ],
                ],
                [
                    [
                        'text' => 'вернуться в главное меню'
                    ],
                    [
                        'text' => 'добавить в корзину'
                    ],
                ],
            ];
        }

        if (is_array($paginate) && !isset($paginate['next'])) {
            $nav = [
                [
                    [
                        'text' => 'предыдущий'
                    ],
                ],
                [
                    [
                        'text' => 'вернуться в главное меню'
                    ],
                    [
                        'text' => 'добавить в корзину'
                    ],
                ],
            ];
        }

        return $nav;
    }
}
