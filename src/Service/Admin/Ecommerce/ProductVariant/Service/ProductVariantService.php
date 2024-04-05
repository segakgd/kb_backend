<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\ProductVariant\Service;

use App\Entity\Ecommerce\ProductVariant;
use App\Repository\Ecommerce\ProductVariantRepository;

class ProductVariantService
{
    public function __construct(private readonly ProductVariantRepository $productVariantRepository)
    {
    }

    public function save(ProductVariant $productVariant): ProductVariant
    {
        $this->productVariantRepository->saveAndFlush($productVariant);

        return $productVariant;
    }

    public function getByProductAndId(int $product, int $id): ?ProductVariant
    {
        return $this->productVariantRepository->findOneBy(['id' => $id, 'product' => $product]);
    }

    public function getById(int $id): ?ProductVariant
    {
        return $this->productVariantRepository->find($id);
    }
}
