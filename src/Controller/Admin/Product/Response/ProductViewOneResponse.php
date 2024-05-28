<?php

namespace App\Controller\Admin\Product\Response;

use App\Controller\Admin\Product\DTO\Response\ProductRespDto;
use App\Entity\Ecommerce\Product;
use App\Helper\Ecommerce\Product\ProductCategoryHelper;
use App\Helper\Ecommerce\Product\ProductVariantHelper;

class ProductViewOneResponse
{
    public function makeResponse(Product $product): ProductRespDto
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
}