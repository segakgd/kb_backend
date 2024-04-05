<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Product\Manager;

use App\Controller\Admin\Product\DTO\Request\ProductCategoryReqDto;
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
    ): Product // todo -> как будто много логики на одного productManager
    {
        $product = (new Product())
            ->setName($productReqDto->getName())
            ->setProjectId($project->getId())
            ->setDescription($productReqDto->getDescription())
            ->setVisible($productReqDto->isVisible());

        $categories = $productReqDto->getCategories();

        if (!empty($categories)) {
            $categories = $this->productCategoryService->getByProjectIdAndReqDto($project->getId(), $categories);

            foreach ($categories as $category) {
                $product->addCategory($category);
            }
        }

        $productVariants = $productReqDto->getVariants();

        if (!empty($productVariants)) {
            foreach ($productVariants as $productVariant) {
                $productVariantEntity = (new ProductVariant())
                    ->setName($productVariant->getName())
                    ->setCount($productVariant->getCount())
                    ->setArticle($productVariant->getArticle())
                    ->setPrice($productVariant->getPrice())
                    ->setImage($productVariant->getImages());

                $product->addVariant($productVariantEntity);
            }
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

    public function update(ProductReqDto $productReqDto, Product $product, Project $project): void
    {
        $categoriesDto = $productReqDto->getCategories();

        /** @var ProductCategory[] $categories */
        $categories = $this->productCategoryService->getByProjectIdAndReqDto($project->getId(), $categoriesDto);

        foreach ($categories as $category) {
            $categoryDto = array_filter(
                $categoriesDto,
                function (ProductCategoryReqDto $productCategoryDto) use ($category) {
                    return $category->getId() === $productCategoryDto->getId();
                }
            );

            $categoryDto = current($categoryDto);

            if (false !== $categoryDto) {
                /** @var ProductCategoryReqDto $categoryDto */
                $category->setName($categoryDto->getName());
                $category->markAsUpdated();
            }
        }


        $variantsDto = $product->getVariants();

        foreach ($variantsDto as $variantDto) {
            if (null === $variantDto->getId()) {
                $productVariantEntity = (new ProductVariant())
                    ->setName($variantDto->getName())
                    ->setCount($variantDto->getCount())
                    ->setArticle($variantDto->getArticle())
                    ->setPrice($variantDto->getPrice())
                    ->setImage($variantDto->getImage());

                $product->addVariant($productVariantEntity);
            } elseif (null !== $variantDto->getId()) {
                $variantEntity = $this->productVariantService->getByProductAndId(
                    $product->getId(),
                    $variantDto->getId()
                );

                if (null !== $variantEntity) {
                    $variantEntity
                        ->markAsUpdated()
                        ->setName($variantDto->getName())
                        ->setCount($variantDto->getCount())
                        ->setPrice($variantDto->getPrice())
                        ->setArticle($variantDto->getArticle())
                        ->setImage($variantDto->getImage())
                        ->setActive($variantDto->isActive())
                    ;
                }

                $this->productVariantService->save($variantEntity);
            }
        }

        $product
            ->markAsUpdated()
            ->setName($productReqDto->getName())
            ->setDescription($productReqDto->getName())
            ->setVisible($productReqDto->isVisible())
        ;

        $this->productService->save($product);
    }
}
