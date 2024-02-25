<?php

namespace App\Service\Admin\Ecommerce\Product;

class ProductService implements ProductServiceInterface
{
    public function getProducts(): array
    {
        return [
            $this->getFakeProduct(
                'Продукт 1',
                "https://proprikol.ru/wp-content/uploads/2020/05/kartinki-glaza-anime-20.jpg"
            ),
            $this->getFakeProduct(
                'Продукт 2',
                "https://sopranoclub.ru/images/190-epichnyh-anime-artov/file48822.jpg"
            ),
        ];
    }

    private function getFakeProduct(string $name, string $imageUri): array
    {
        return [
            'name' => $name,
            'amount' => rand(40, 500),
            'availableCount' => rand(1, 50),
            'mainImage' => $imageUri,
        ];
    }
}
