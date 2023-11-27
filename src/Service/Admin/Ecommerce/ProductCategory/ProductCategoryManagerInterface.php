<?php

namespace App\Service\Admin\Ecommerce\ProductCategory;

use App\Dto\Ecommerce\ProductCategoryDto;
use App\Entity\Ecommerce\ProductCategory;

interface ProductCategoryManagerInterface
{
    public function getAll(int $projectId): array;

    public function getOne(int $projectId, int $productCategoryId): ?ProductCategory;

    public function add(ProductCategoryDto $productCategoryDto, int $projectId): ProductCategory;

    public function update(ProductCategoryDto $productCategoryDto, int $projectId, int $productCategoryId): ProductCategory;

    public function remove(int $projectId, int $productCategoryId): bool;
}