<?php

namespace App\Service\Admin\Ecommerce\ProductVariant;

use App\Dto\deprecated\Ecommerce\ProductVariantDto;
use App\Entity\Ecommerce\ProductVariant;

interface ProductVariantManagerInterface
{
    public function add(ProductVariantDto $dto): ProductVariant;

    public function update(ProductVariantDto $dto): ?ProductVariant;
}
