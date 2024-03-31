<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\ProductVariant;

use App\Entity\Ecommerce\ProductVariant;
use App\Repository\Ecommerce\ProductVariantRepository;

class ProductVariantService
{
    public function __construct(private readonly ProductVariantRepository $productVariantRepository)
    {
    }

    public function getById(int $id): ?ProductVariant
    {
        return $this->productVariantRepository->find($id);
    }
}
