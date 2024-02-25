<?php

namespace App\Service\Admin\Ecommerce\Product;

use App\Repository\Ecommerce\ProductCategoryEntityRepository;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductCategoryEntityRepository $productCategoryEntityRepository,
    ) {
    }

    public function getProducts(): array
    {
        $productCategoryName = 'магнитолы';
        $pageNow = 2;
        $nowProductId = 1;

        $productCategory = $this->productCategoryEntityRepository->findOneBy(
            [
                'name' => $productCategoryName,
            ]
        );

        $products = [];

        foreach ($productCategory->getProducts() as $product) {
            $variants = [];

            foreach ($product->getVariants() as $productVariant) {
                $price = $productVariant->getPrice();

                $variants[] = [
                    'name' => $productVariant->getName(),
                    'amount' => $price['price'],
                    'availableCount' => $productVariant->getCount(),
                ];
            }

            $products[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'mainImage' => 'https://sopranoclub.ru/images/190-epichnyh-anime-artov/file48822.jpg',
                'variants' => $variants
            ];
        }

        $total = count($products);
        $prevPage = ($pageNow > 1) ? $pageNow - 1: null;
        $nextPage = ($pageNow < $total) ? $pageNow + 1: null;

        return [
            'products' => $products,
            'paginate' => [
                'prev' => $prevPage,
                'now' => $pageNow,
                'next' => $nextPage,
                'total' => $total,
            ]
        ];
    }
}
