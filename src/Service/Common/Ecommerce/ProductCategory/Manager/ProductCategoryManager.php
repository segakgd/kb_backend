<?php

declare(strict_types=1);

namespace App\Service\Common\Ecommerce\ProductCategory\Manager;

use App\Controller\Admin\ProductCategory\Request\ProductCategoryRequest;
use App\Entity\Ecommerce\ProductCategory;
use App\Entity\User\Project;
use App\Exception\Ecommerce\ProductCategoryExistException;
use App\Service\Common\Ecommerce\ProductCategory\Service\ProductCategoryService;
use Exception;

readonly class ProductCategoryManager implements ProductCategoryManagerInterface
{
    public function __construct(private ProductCategoryService $productCategoryService) {}

    /**
     * @throws ProductCategoryExistException
     * @throws Exception
     */
    public function create(ProductCategoryRequest $categoryReqDto, Project $project): ProductCategory
    {
        $existingCategory = $this->productCategoryService->getCategoryByNameAndProjectId(
            $categoryReqDto->getName(),
            $project->getId()
        );

        if (null !== $existingCategory) {
            throw new ProductCategoryExistException(
                sprintf(
                    'Product category for project id %d with %s name exist',
                    $project->getId(),
                    $categoryReqDto->getName()
                )
            );
        }

        $productCategory = (new ProductCategory())
            ->setName($categoryReqDto->getName())
            ->setProjectId($project->getId());

        return $this->productCategoryService->save($productCategory);
    }

    public function remove(ProductCategory $productCategory): void
    {
        $this->productCategoryService->remove($productCategory);
    }

    public function getAll(Project $project): array
    {
        return $this->productCategoryService->getAllByProjectId($project->getId());
    }

    public function update(
        ProductCategoryRequest $categoryReqDto,
        ProductCategory $productCategory,
        Project $project,
    ): ProductCategory {
        $productCategory->setName($categoryReqDto->getName());

        return $this->productCategoryService->save($productCategory);
    }
}
