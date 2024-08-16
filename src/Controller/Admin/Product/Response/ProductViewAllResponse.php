<?php

namespace App\Controller\Admin\Product\Response;

use App\Controller\Admin\Product\DTO\Response\ProductResponse;
use App\Controller\Admin\Product\Mapper\ProductVariantMapper;
use App\Controller\Admin\ProductCategory\Response\ProductCategoryResponse;
use App\Entity\Ecommerce\Product;
use Exception;

class ProductViewAllResponse
{
    public function mapArrayToResponse(array $products): array
    {
        return array_map(function (Product $product) {
            return self::mapToResponse($product);
        }, $products);
    }

    /**
     * @throws Exception
     */
    private static function mapToResponse(Product $product): ProductResponse
    {
        $categoriesDto = ProductCategoryResponse::mapCollection($product->getCategories());

        $variantsDto = ProductVariantMapper::mapArrayToResponse($product->getVariants()->toArray());

        return (new ProductResponse())
            ->setName($product->getName())
            ->setId($product->getId())
            ->setVisible($product->isVisible())
            ->setDescription($product->getDescription())
            ->setCategories($categoriesDto)
            ->setVariants($variantsDto);
    }
}
