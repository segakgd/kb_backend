<?php

namespace App;

use App\Entity\Ecommerce\ProductVariant;

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
