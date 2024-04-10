<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Product\Manager;

use App\Controller\Admin\Product\DTO\Request\ProductReqDto;
use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductCategory;
use App\Entity\Ecommerce\ProductVariant;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\Admin\Ecommerce\ProductVariant\Service\ProductVariantService;

class ProductManager implements ProductManagerInterface
{
    public function __construct(
        private readonly ProductCategoryService $productCategoryService,
        private readonly ProductService $productService,
        private readonly ProductVariantService $productVariantService,
    ) {
    }

    public function create(
        ProductReqDto $productReqDto,
        Project $project
    ): Product {
        $product = (new Product())
            ->setName($productReqDto->getName())
            ->setProjectId($project->getId())
            ->setDescription($productReqDto->getDescription())
            ->setVisible($productReqDto->isVisible());

        $categories = $this->productCategoryService->getByProjectIdAndReqDto(
            $project->getId(),
            $productReqDto->getCategories()
        );

        foreach ($categories as $category) {
            $product->addCategory($category);
        }

        foreach ($productReqDto->getVariants() as $productVariant) {
            $productVariantEntity = (new ProductVariant())
                ->setName($productVariant->getName())
                ->setCount($productVariant->getCount())
                ->setArticle($productVariant->getArticle())
                ->setPrice($productVariant->getPrice())
                ->setImage($productVariant->getImages())
                ->setIsLimitless($productVariant->isLimitless())
                ->markAsUpdated()
            ;

            $product->addVariant($productVariantEntity);
        }

        $this->productService->save($product);

        return $product;
    }

    public function remove(Product $product): void
    {
        $this->productService->remove($product);
    }

    public function getAll(Project $project): array
    {
        return $this->productService->getAllByProject($project);
    }

    public function update(ProductReqDto $productReqDto, Product $product, Project $project): void // todo -> clean up
    {
        $categoriesDto = $productReqDto->getCategories();

        /** @var ProductCategory[] $categories */
        $categories = $this->productCategoryService->getByProjectIdAndReqDto($project->getId(), $categoriesDto);

        $categoriesArray = [];

        foreach ($categories as $categoryEntity) {
            $categoriesArray[$categoryEntity->getId()] = $categoryEntity;
        }

        foreach ($categoriesDto as $categoryDto) {
            $categoryEntity = $categoriesArray[$categoryDto->getId()] ?? null;
            $categoryEntity
                ?->setName($categoryDto->getName())
                ->markAsUpdated();

            if (null !== $categoryEntity) {
                $categoryEntity->addProduct($product);
                $this->productCategoryService->save($categoryEntity);
            }
        }

        $product = $this->productVariantService->handleBatchUpdate($product, $productReqDto->getVariants());

        $product
            ->markAsUpdated()
            ->setName($productReqDto->getName())
            ->setDescription($productReqDto->getName())
            ->setVisible($productReqDto->isVisible());

        $this->productService->save($product);
    }
}
