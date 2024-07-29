<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product\Mapper;

use App\Controller\Admin\Product\DTO\Response\ProductVariantRespDto;
use App\Entity\Ecommerce\ProductVariant;

class ProductVariantMapper
{
    public static function mapToResponse(ProductVariant $variant): ProductVariantRespDto
    {
        return (new ProductVariantRespDto())
            ->setArticle($variant->getArticle())
            ->setPrice($variant->getPrice())
            ->setCount($variant->getCount())
            ->setImages($variant->getImage())
            ->setName($variant->getName());
    }

    public static function mapArrayToResponse(array $variants): array
    {
        return array_map(function (ProductVariant $productCategory) {
            return self::mapToResponse($productCategory);
        }, $variants);
    }
}
