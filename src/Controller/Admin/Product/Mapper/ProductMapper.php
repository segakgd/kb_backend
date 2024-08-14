<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product\Mapper;

use App\Controller\Admin\Product\DTO\Response\ProductResponse;
use App\Entity\Ecommerce\Product;

class ProductMapper
{
    public static function mapToResponse(Product $product): ProductResponse
    {
        $categoriesDto = ProductCategoryMapper::mapArrayToResponse($product->getCategories()->toArray());

        $variantsDto = ProductVariantMapper::mapArrayToResponse($product->getVariants()->toArray());

        return (new ProductResponse())
            ->setName($product->getName())
            ->setId($product->getId())
            ->setVisible($product->isVisible())
            ->setDescription($product->getDescription())
            ->setCategories($categoriesDto)
            ->setVariants($variantsDto);
    }

    public static function mapArrayToResponse(array $products): array
    {
        return array_map(function (Product $product) {
            return self::mapToResponse($product);
        }, $products);
    }
}
