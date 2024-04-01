<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\ProductCategory\Mapper;

use App\Controller\Admin\Product\DTO\Response\ProductCategoryRespDto;
use App\Entity\Ecommerce\ProductCategory;

class ProductCategoryMapper
{
    public function mapToResponse(ProductCategory $productCategory): ProductCategoryRespDto
    {
        return (new ProductCategoryRespDto())
            ->setName($productCategory->getName())
            ->setId($productCategory->getId());
    }

    public function mapArrayToResponse(array $productCategories): array
    {
        return array_map(function (ProductCategory $productCategory) {
            return $this->mapToResponse($productCategory);
        }, $productCategories);
    }
}
