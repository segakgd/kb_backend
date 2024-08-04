<?php

declare(strict_types=1);

namespace App\Service\Common\Ecommerce\ProductCategory\Manager;

use App\Controller\Admin\ProductCategory\DTO\Request\ProductCategoryReqDto;
use App\Entity\Ecommerce\ProductCategory;
use App\Entity\User\Project;

interface ProductCategoryManagerInterface
{
    public function create(ProductCategoryReqDto $categoryReqDto, Project $project): ProductCategory;

    public function remove(ProductCategory $productCategory): void;

    public function getAll(Project $project): array;

    public function update(
        ProductCategoryReqDto $categoryReqDto,
        ProductCategory $productCategory,
        Project $project
    ): ProductCategory;
}
