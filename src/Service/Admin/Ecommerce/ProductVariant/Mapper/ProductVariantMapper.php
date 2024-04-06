<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\ProductVariant\Mapper;

use App\Controller\Admin\Product\DTO\Response\ProductVariantRespDto;
use App\Entity\Ecommerce\ProductVariant;

class ProductVariantMapper
{
    public function mapToResponse(ProductVariant $variant): ProductVariantRespDto
    {
        return (new ProductVariantRespDto())
            ->setArticle($variant->getArticle())
            ->setPrice($variant->getPrice())
            ->setCount($variant->getCount())
            ->setImages($variant->getImage())
            ->setName($variant->getName());
    }

    public function mapArrayToResponse(array $variants): array
    {
        return array_map(function (ProductVariant $productCategory) {
            return $this->mapToResponse($productCategory);
        }, $variants);
    }
}
