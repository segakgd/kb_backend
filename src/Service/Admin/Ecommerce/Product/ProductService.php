<?php

namespace App\Service\Admin\Ecommerce\Product;

use App\Helper;
use App\Repository\Ecommerce\ProductCategoryEntityRepository;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductCategoryEntityRepository $productCategoryEntityRepository,
    ) {
    }

    // todo типизация

    /**
     * @throws \Exception
     */
    public function getProductsByCategory($pageNow, $categoryName): array // todo переделать в $categoryId (хранить id совместно с названием)
    {
        $productCategory = $this->productCategoryEntityRepository->findOneBy(
            [
                'name' => $categoryName,
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

        $paginate = Helper::buildPaginate($pageNow, $total);

        return [
            'products' => $products,
            'paginate' => $paginate,
        ];
    }
}
