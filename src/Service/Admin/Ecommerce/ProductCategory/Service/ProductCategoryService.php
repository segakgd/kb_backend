<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\ProductCategory\Service;

use App\Entity\Ecommerce\ProductCategory;
use App\Repository\Ecommerce\ProductCategoryEntityRepository;

class ProductCategoryService implements ProductCategoryServiceInterface
{
    public function __construct(
        private readonly ProductCategoryEntityRepository $productCategoryEntityRepository,
    )
    {
    }

    public function getAllByProjectId(int $projectId): array
    {
        return $this->productCategoryEntityRepository->findBy(['projectId' => $projectId]);
    }

    public function save(ProductCategory $category): ProductCategory
    {
        $this->productCategoryEntityRepository->saveAndFlush($category);

        return $category;
    }

    public function remove(ProductCategory $category): void
    {
        $this->productCategoryEntityRepository->removeAndFlush($category);
    }

    public function getCategoryByNameAndProjectId(string $categoryName, int $projectId)
    {
        return $this->productCategoryEntityRepository->findOneBy(
            ['name' => $categoryName, 'projectId' => $projectId]
        );
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
