<?php

namespace App;

use App\Entity\Ecommerce\ProductVariant;
use Exception;

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

    /**
     * @throws Exception
     */
    public static function buildPaginate(int $page, int $maxPage): array
    {
        if ($maxPage < $page) {
            throw new Exception('max page < page');
        }

        $prevPage = ($page > 1) ? $page - 1: null;
        $nextPage = ($page < $maxPage) ? $page + 1: null;

        return [
            'prev' => $prevPage,
            'now' => $page,
            'next' => $nextPage,
            'total' => $maxPage,
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

    public static function getProductNav(?array $paginate = null): array
    {
        $nav = [
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

        if (is_array($paginate) && !isset($paginate['prev'])) {
            $nav = [
                [
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

        if (is_array($paginate) && !isset($paginate['next'])) {
            $nav = [
                [
                    [
                        'text' => 'предыдущий'
                    ],
                    [
                        'text' => 'подробнее о товаре'
                    ],
                ],
                [
                    [
                        'text' => 'вернуться в главное меню'
                    ],
                ],
            ];
        }

        return $nav;
    }

    public static function translate(string $key): string
    {
        return match ($key) {
            'show.shop.products.category' => 'Приветственное сообщение, показываем доступные категории',
            'shop.products.category' => 'Показываем товары по выбранной категории',
            'shop.products' => 'Товары по выбранной катигории',
            'shop.product' => 'Просмотр конкретного продукта',
            default => $key,
        };
    }

    public static function renderProductMessage(array $product): string
    {
        $name = $product['name'];

        $message = "Название: $name \n\n";

        $variants = $product['variants'];

        /** @var ProductVariant $variant */
        foreach ($variants as $variant) {
            $name = $variant['name'];
            $price = $variant['amount'];
            $count = $variant['availableCount'];

            $message .= "Вариант: $name \n";
            $message .= "Цена: $price \n";
            $message .= "Доступное количество: $count \n";
            $message .= "\n";
        }

        return $message;
    }
}
