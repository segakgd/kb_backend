<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\ProductCategory\Service;

use App\Controller\Admin\Product\DTO\Request\ProductCategoryReqDto;
use App\Entity\Ecommerce\ProductCategory;
use App\Repository\Ecommerce\ProductCategoryEntityRepository;

readonly class ProductCategoryService implements ProductCategoryServiceInterface
{
    public function __construct(
        private ProductCategoryEntityRepository $productCategoryEntityRepository,
    ) {
    }

    public function getByProjectIdAndReqDto(int $projectId, array $categories): array
    {
        $categoriesId = array_map(function (ProductCategoryReqDto $productCategory) {
            return $productCategory->getId();
        }, $categories);

        if (empty($categoriesId)) {
            return [];
        }

        return $this->productCategoryEntityRepository->findBy(['projectId' => $projectId, 'id' => $categoriesId]);
    }

    public function getAllByProjectId(int $projectId): array
    {
        return $this->productCategoryEntityRepository->findBy(['projectId' => $projectId]);
    }

    public function getAllByProjectIdAndIDs(int $projectId, array $ids): array
    {
        return $this->productCategoryEntityRepository->findBy(['projectId' => $projectId, 'id' => $ids]);
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
