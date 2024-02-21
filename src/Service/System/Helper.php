<?php

namespace App\Service\System;

class Helper
{
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
            'подробнее о товаре',
            'следующий',
            'вернуться в главное меню',
        ];
    }

    public static function getProductNav(): array
    {
        return [
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
                    'text' => 'вернуться в главное меню'
                ],
            ],
        ];
    }
}
