<?php

declare(strict_types=1);

namespace App\Helper\Ecommerce;

use App\Controller\Admin\Product\DTO\Response\ProductCategoryRespDto;
use App\Entity\Ecommerce\ProductCategory;

class ProductCategoryHelper
{
    public static function mapToResponse(ProductCategory $productCategory): ProductCategoryRespDto
    {
        return (new ProductCategoryRespDto())
            ->setName($productCategory->getName())
            ->setId($productCategory->getId());
    }

    public static function mapArrayToResponse(array $productCategories): array
    {
        return array_map(function (ProductCategory $productCategory) {
            return $this->mapToResponse($productCategory);
        }, $productCategories);
    }
}
