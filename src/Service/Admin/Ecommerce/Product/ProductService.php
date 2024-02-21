<?php

namespace App\Service\Admin\Ecommerce\Product;

class ProductService implements ProductServiceInterface
{
    public function getProducts(): array
    {
        return [
            $this->getFakeProduct(
                'Продукт 1',
                "https://w.forfun.com/fetch/2f/2f17c8186f5e498000579700660c5734.jpeg"
            ),
            $this->getFakeProduct(
                'Продукт 2',
                "https://w.forfun.com/fetch/da/daf8eb568fea522f6701fb9c66378cdc.jpeg"
            ),
            $this->getFakeProduct(
                'Продукт 3',
                "https://w.forfun.com/fetch/b4/b4ee94aa20bd9af0dafd3b4d62332c08.jpeg"
            ),
            $this->getFakeProduct(
                'Продукт 4',
                "https://w.forfun.com/fetch/8e/8e0c065fe670d5b6c49de9f5fea277a4.jpeg"
            ),
            $this->getFakeProduct(
                'Продукт 5',
                "https://r4.wallpaperbetter.com/wallpaper/1008/573/262/the-sky-clouds-trees-landscape-wallpaper-dcb4070adceb26342be4e9ebf685ac19.jpg"
            ),
        ];
    }

    private function getFakeProduct(string $name, string $imageUri): array
    {
        return [
            'name' => $name,
            'amount' => round(40, 500),
            'availableCount' => 2,
            'mainImage' => $imageUri,
        ];
    }
}
