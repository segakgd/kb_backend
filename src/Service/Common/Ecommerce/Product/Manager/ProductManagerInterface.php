<?php

declare(strict_types=1);

namespace App\Service\Common\Ecommerce\Product\Manager;

use App\Controller\Admin\Product\DTO\Request\ProductRequest;
use App\Entity\Ecommerce\Product;
use App\Entity\User\Project;

interface ProductManagerInterface
{
    public function create(ProductRequest $productReqDto, Project $project): Product;

    public function remove(Product $product): void;

    public function getAll(Project $project): array;

    public function update(ProductRequest $productReqDto, Product $product, Project $project): void;
}
