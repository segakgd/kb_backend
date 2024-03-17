<?php

namespace App\Service\Admin\Ecommerce\ProductCategory;

use App\Entity\Ecommerce\ProductCategory;
use App\Repository\Ecommerce\ProductCategoryEntityRepository;

class ProductCategoryService implements ProductCategoryServiceInterface
{
    public function __construct(
        private readonly ProductCategoryEntityRepository $productCategoryEntityRepository,
    ) {
    }

    public function getCategoryByName(string $categoryName): ?ProductCategory
    {
        return $this->productCategoryEntityRepository->findOneBy(
            [
                'name' => $categoryName,
            ]
        );
    }

    public function getAvailableCategory(int $projectId): array
    {
        $productCategories = $this->productCategoryEntityRepository->findBy(
            [
                'projectId' => $projectId,
            ]
        );

        $availableCategory = [];

        foreach ($productCategories as $productCategory) {
            $availableCategory[] = $productCategory->getName();
        }

        return $availableCategory;
    }
}
