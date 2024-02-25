<?php

namespace App\Service\Admin\Ecommerce\ProductCategory;

use App\Repository\Ecommerce\ProductCategoryEntityRepository;

class ProductCategoryService implements ProductCategoryServiceInterface
{
    public function __construct(
        private readonly ProductCategoryEntityRepository $productCategoryEntityRepository,
    ) {
    }

    public function getAvailableCategory(): array
    {
        $productCategories = $this->productCategoryEntityRepository->findBy(
            [
                'projectId' => 4842,
            ]
        );

        $availableCategory = [];

        foreach ($productCategories as $productCategory) {
            $availableCategory[] = $productCategory->getName();
        }

        return $availableCategory;
    }
}
