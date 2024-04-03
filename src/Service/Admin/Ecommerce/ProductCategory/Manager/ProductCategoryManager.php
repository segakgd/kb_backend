<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\ProductCategory\Manager;

use App\Controller\Admin\ProductCategory\DTO\Request\ProductCategoryReqDto;
use App\Entity\Ecommerce\ProductCategory;
use App\Entity\User\Project;
use App\Exception\Ecommerce\ProductCategoryExistException;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use Exception;

class ProductCategoryManager implements ProductCategoryManagerInterface
{
    public function __construct(private readonly ProductCategoryService $productCategoryService)
    {
    }

    /**
     * @throws ProductCategoryExistException
     * @throws Exception
     */
    public function create(ProductCategoryReqDto $categoryReqDto, Project $project): ProductCategory
    {
        $existingCategory = $this->productCategoryService->getCategoryByNameAndProjectId(
            $categoryReqDto->getName(),
            $project->getId()
        );

        if (null !== $existingCategory) {
            throw new ProductCategoryExistException(
                sprintf(
                    'Product category for project %d with %s name exist',
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
        ProductCategoryReqDto $categoryReqDto,
        ProductCategory $productCategory,
        Project $project
    ): ProductCategory {
        $productCategory
            ->setName($categoryReqDto->getName())
            ->markAsUpdated();

        return $this->productCategoryService->save($productCategory);
    }
}
