<?php

namespace App\Controller\Admin\ProductCategory\Response;

use App\Controller\Admin\Product\DTO\Response\ProductCategoryResponse;
use App\Entity\Ecommerce\ProductCategory;

class ProductCategoryViewAllResponse
{
    public function mapArrayToResponse(array $productCategories): array
    {
        return array_map(function (ProductCategory $productCategory) {
            return (new ProductCategoryResponse())
                ->setName($productCategory->getName())
                ->setId($productCategory->getId());
        }, $productCategories);
    }
}
