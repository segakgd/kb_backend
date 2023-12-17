<?php

namespace App\Service\Admin\Ecommerce\ProductCategory;

use App\Dto\deprecated\Ecommerce\ProductCategoryDto;
use App\Entity\Ecommerce\ProductCategory;
use App\Repository\Ecommerce\ProductCategoryEntityRepository;
use Psr\Log\LoggerInterface;
use Throwable;

class ProductCategoryManager implements ProductCategoryManagerInterface
{
    public function __construct(
        private readonly ProductCategoryEntityRepository $productCategoryEntityRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getAll(int $projectId): array
    {
        return $this->productCategoryEntityRepository->findBy(
            [
                'projectId' => $projectId
            ]
        );
    }

    public function getOne(int $projectId, int $productCategoryId): ?ProductCategory
    {
        return $this->productCategoryEntityRepository->findOneBy(
            [
                'id' => $productCategoryId,
                'projectId' => $projectId
            ]
        );
    }

    public function add(ProductCategoryDto $productCategoryDto, int $projectId): ProductCategory
    {
        $productCategory = (new ProductCategory);

        if ($name = $productCategoryDto->getName()){
            $productCategory->setName($name);
        }

        $productCategory->setProjectId($projectId);

        $this->productCategoryEntityRepository->saveAndFlush($productCategory);

        return $productCategory;
    }

    public function update(ProductCategoryDto $productCategoryDto, int $projectId, int $productCategoryId): ProductCategory
    {
        $productCategory = $this->getOne($projectId, $productCategoryId);

        if ($name = $productCategoryDto->getName()){
            $productCategory->setName($name);
        }

        $this->productCategoryEntityRepository->saveAndFlush($productCategory);

        return $productCategory;
    }

    public function remove(int $projectId, int $productCategoryId): bool
    {
        $productCategory = $this->getOne($projectId, $productCategoryId);

        try {
            if ($productCategory){
                $this->productCategoryEntityRepository->removeAndFlush($productCategory);
            }

        } catch (Throwable $exception){
            $this->logger->error($exception->getMessage());

            return false;
        }

        return true;
    }
}
