<?php

namespace App\Controller\Admin\ProductCategory\Response;

use App\Controller\Admin\Product\DTO\Response\ProductCategoryRespDto;
use App\Entity\Ecommerce\ProductCategory;

class ProductCategoryViewAllResponse
{
    public function mapArrayToResponse(array $productCategories): array
    {
        return array_map(function (ProductCategory $productCategory) {
            return (new ProductCategoryRespDto())
                ->setName($productCategory->getName())
                ->setId($productCategory->getId());
        }, $productCategories);
    }
}