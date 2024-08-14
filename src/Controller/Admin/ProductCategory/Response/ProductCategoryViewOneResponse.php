<?php

namespace App\Controller\Admin\ProductCategory\Response;

use App\Controller\Admin\Product\DTO\Response\ProductCategoryResponse;
use App\Entity\Ecommerce\ProductCategory;

class ProductCategoryViewOneResponse
{
    public function makeResponse(ProductCategory $productCategory): ProductCategoryResponse
    {
        return (new ProductCategoryResponse())
            ->setName($productCategory->getName())
            ->setId($productCategory->getId());
    }
}
