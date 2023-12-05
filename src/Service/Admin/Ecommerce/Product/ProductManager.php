<?php

namespace App\Service\Admin\Ecommerce\Product;

use App\Dto\deprecated\Ecommerce\ProductDto;
use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductCategory;
use App\Repository\Ecommerce\ProductCategoryEntityRepository;
use App\Repository\Ecommerce\ProductEntityRepository;
use App\Service\Admin\Ecommerce\ProductCategory\ProductCategoryManager;
use App\Service\Admin\Ecommerce\ProductVariant\ProductVariantManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class ProductManager implements ProductManagerInterface
{
    public function __construct(
        private readonly ProductEntityRepository $productEntityRepository,
        private readonly ProductCategoryEntityRepository $productCategoryEntityRepository,
        private readonly ProductCategoryManager $productCategoryService,
        private readonly ProductVariantManagerInterface $productVariantService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function getAll(int $projectId): array
    {
        return $this->productEntityRepository->findBy(
            [
                'projectId' => $projectId
            ]
        );
    }

    public function getOne(int $projectId, int $productId): ?Product
    {
        return $this->productEntityRepository->findOneBy(
            [
                'id' => $productId,
                'projectId' => $projectId
            ]
        );
    }

    public function add(ProductDto $productDto, int $projectId): Product
    {
        $productEntity = $this->mapToExistEntity($productDto, (new Product));

        $productEntity->setProjectId($projectId);

        $this->productEntityRepository->saveAndFlush($productEntity);

        return $productEntity;
    }

    public function update(ProductDto $productDto, int $projectId, int $productId): Product
    {
        $productEntity = $this->getOne($projectId, $productId);

        $productEntity = $this->mapToExistEntity($productDto, $productEntity);

        $this->productEntityRepository->saveAndFlush($productEntity);

        return $productEntity;
    }

    public function remove(int $projectId, int $productId): bool
    {
        $productEntity = $this->getOne($projectId, $productId);

        try {
            if ($productEntity){
                $this->productEntityRepository->removeAndFlush($productEntity);
            }

        } catch (Throwable $exception){
            $this->logger->error($exception->getMessage());

            return false;
        }

        return true;
    }

    public function addInCategory(Product $product, ProductCategory $productCategory): ProductCategory
    {
        $productCategory->addProduct($product);

        $this->productCategoryEntityRepository->saveAndFlush($productCategory);

        return $productCategory;
    }

    public function isExist(int $id): bool
    {
        return (bool) $this->productEntityRepository->find($id);
    }

    public function mapToExistEntity(ProductDto $dto, Product $entity): Product
    {
        if ($projectId = $dto->getProjectId()){
            $entity->setProjectId($projectId);
        }

        if ($categoriesDto = $dto->getCategories()){
            $categoriesEntity = $entity->getCategories();

            if ($categoriesEntity->count() > 0){
                foreach ($categoriesDto as $categoryDto){
                    $isUpdated = false;

                    foreach ($categoriesEntity as $categoryEntity){
                        if ($categoryDto->getId() === $categoryEntity->getId()){
                            $productCategory = $this->productCategoryService->update($categoryDto, $categoryEntity->getProjectId(), $categoryEntity->getId());

                            $entity->addCategory($productCategory);

                            $isUpdated = true;
                        }
                    }

                    if (!$isUpdated){
                        $productCategory = $this->productCategoryService->add($categoryDto, $entity->getProjectId());

                        $entity->addCategory($productCategory);
                    }
                }

            } else {
                foreach ($categoriesDto as $categoryDto){
                    $productCategory = $this->productCategoryService->add($categoryDto, $entity->getProjectId());

                    $entity->addCategory($productCategory);
                }
            }
        }

        if ($variantsDto = $dto->getVariants()){
            if ($variantsEntity = $entity->getVariants()){
                foreach ($variantsDto as $variantDto){
                    $isUpdated = false;

                    foreach ($variantsEntity as $variantEntity){
                        if ($variantDto->getId() === $variantEntity->getId()){
                            $productVariant = $this->productVariantService->update($variantDto);

                            $entity->addVariant($productVariant);

                            $isUpdated = true;
                        }
                    }

                    if (!$isUpdated){
                        $productVariant = $this->productVariantService->add($variantDto);

                        $entity->addVariant($productVariant);
                    }
                }
            } else {
                foreach ($variantsDto as $variantDto){
                    $productVariant = $this->productVariantService->add($variantDto);

                    $entity->addVariant($productVariant);
                }
            }
        }

        return $entity;
    }
}
