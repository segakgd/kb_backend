<?php

declare(strict_types=1);

namespace App\Service\Common\Ecommerce\ProductCategory\Manager;

use App\Controller\Admin\ProductCategory\Request\ProductCategoryRequest;
use App\Entity\Ecommerce\ProductCategory;
use App\Entity\User\Project;

interface ProductCategoryManagerInterface
{
    public function create(ProductCategoryRequest $categoryReqDto, Project $project): ProductCategory;

    public function remove(ProductCategory $productCategory): void;

    public function getAll(Project $project): array;

    public function update(
        ProductCategoryRequest $categoryReqDto,
        ProductCategory $productCategory,
        Project $project
    ): ProductCategory;
}
