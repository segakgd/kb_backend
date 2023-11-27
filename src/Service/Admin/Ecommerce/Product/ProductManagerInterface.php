<?php

namespace App\Service\Admin\Ecommerce\Product;

use App\Dto\Ecommerce\ProductDto;
use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductCategory;

interface ProductManagerInterface
{
    public function getAll(int $projectId): array;

    public function getOne(int $projectId, int $productId): ?Product;

    public function add(ProductDto $productDto, int $projectId): Product;

    public function update(ProductDto $productDto, int $projectId, int $productId): Product;

    public function remove(int $projectId, int $productId): bool;

    public function addInCategory(Product $product, ProductCategory $productCategory): ProductCategory;

    public function isExist(int $id): bool;
}