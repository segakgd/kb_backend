<?php

declare(strict_types=1);

namespace App\Helper\Ecommerce;

use App\Controller\Admin\Product\DTO\Response\ProductRespDto;
use App\Entity\Ecommerce\Product;

class ProductHelper
{
    public static function mapToResponse(Product $product): ProductRespDto
    {
        $categoriesDto = ProductCategoryHelper::mapArrayToResponse($product->getCategories()->toArray());

        $variantsDto = ProductVariantHelper::mapArrayToResponse($product->getVariants()->toArray());

        return (new ProductRespDto())
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
            return $this->mapToResponse($product);
        }, $products);
    }
}
