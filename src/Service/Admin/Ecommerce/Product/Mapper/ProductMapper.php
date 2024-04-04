<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Product\Mapper;

use App\Controller\Admin\Product\DTO\Response\ProductRespDto;
use App\Entity\Ecommerce\Product;
use App\Service\Admin\Ecommerce\ProductCategory\Mapper\ProductCategoryMapper;
use App\Service\Admin\Ecommerce\ProductVariant\Mapper\ProductVariantMapper;

class ProductMapper
{
    public function __construct(
        private readonly ProductCategoryMapper $productCategoryMapper,
        private readonly ProductVariantMapper $productVariantMapper,
    ) {
    }

    public function mapToResponse(Product $product): ProductRespDto
    {
        $categoriesDto = $this->productCategoryMapper->mapArrayToResponse($product->getCategories()->toArray());

        $variantsDto = $this->productVariantMapper->mapArrayToResponse($product->getVariants()->toArray());

        return (new ProductRespDto())
            ->setName($product->getName())
            ->setId($product->getId())
            ->setVisible($product->isVisible())
            ->setDescription($product->getDescription())
            ->setCategories($categoriesDto)
            ->setVariants($variantsDto);
    }

    public function mapArrayToResponse(array $products): array
    {
        return array_map(function (Product $product) {
            return $this->mapToResponse($product);
        }, $products);
    }
}
