<?php

namespace App\Controller\Admin\ProductCategory\Response;

use App\Controller\Admin\Product\DTO\Response\ProductCategoryRespDto;
use App\Entity\Ecommerce\ProductCategory;

class ProductCategoryViewOneResponse
{
    public function makeResponse(ProductCategory $productCategory): ProductCategoryRespDto
    {
        return (new ProductCategoryRespDto())
            ->setName($productCategory->getName())
            ->setId($productCategory->getId());
    }
}
