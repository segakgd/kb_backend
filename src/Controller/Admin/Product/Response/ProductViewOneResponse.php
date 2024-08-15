<?php

namespace App\Controller\Admin\Product\Response;

use App\Controller\Admin\Product\DTO\Response\ProductCategoryResponse;
use App\Controller\Admin\Product\DTO\Response\ProductResponse;
use App\Controller\Admin\Product\Mapper\ProductVariantMapper;
use App\Entity\Ecommerce\Product;
use Exception;

class ProductViewOneResponse
{
    /**
     * @throws Exception
     */
    public function makeResponse(Product $product): ProductResponse
    {
        $categoriesDto = ProductCategoryResponse::mapFromCollection($product->getCategories());

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
