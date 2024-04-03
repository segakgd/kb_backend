<?php

declare(strict_types=1);

namespace App\Service\Admin\Ecommerce\Product\Manager;

use App\Controller\Admin\Product\DTO\Request\ProductReqDto;
use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductVariant;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Admin\Ecommerce\ProductCategory\Service\ProductCategoryService;
use App\Service\Admin\Ecommerce\ProductVariant\Service\ProductVariantService;

class ProductManager implements ProductManagerInterface
{
    public function __construct(
        private readonly ProductCategoryService $productCategoryService,
        private readonly ProductVariantService $productVariantService,
        private readonly ProductService $productService,
    ) {
    }

    public function create(ProductReqDto $productReqDto, Project $project): Product // todo -> как будто много логики на одного productManager
    {
        $product = (new Product())
            ->setName($productReqDto->getName())
            ->setProjectId($project->getId())
            ->setDescription($productReqDto->getDescription())
            ->setVisible($productReqDto->isVisible());

        $categories = $productReqDto->getCategories();

        if (!empty($categories)) {
            $categories = $this->productCategoryService->getByProjectIdAndIDs($project->getId(), $categories);

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
                    ->setImage($productVariant->getImages())
                ;

                $product->addVariant($productVariantEntity);
                $this->productVariantService->save($productVariantEntity);
            }
        }

        $this->productService->save($product);

        return $product;
    }

    public function remove(Product $product): void
    {
        $this->productService->remove($product);
    }
}
