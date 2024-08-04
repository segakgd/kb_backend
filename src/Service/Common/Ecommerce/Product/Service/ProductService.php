<?php

namespace App\Service\Common\Ecommerce\Product\Service;

use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductVariant;
use App\Entity\User\Project;
use App\Repository\Ecommerce\ProductCategoryEntityRepository;
use App\Repository\Ecommerce\ProductEntityRepository;
use App\Repository\Ecommerce\ProductVariantRepository;
use Exception;

readonly class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductEntityRepository $productEntityRepository,
        private ProductCategoryEntityRepository $productCategoryEntityRepository,
        private ProductVariantRepository $productVariantRepository,
    ) {}

    public function getAllByProject(Project $project): array
    {
        return $this->productEntityRepository->findBy(['projectId' => $project->getId()]);
    }

    public function save(Product $product): Product
    {
        $this->productEntityRepository->saveAndFlush($product);

        return $product;
    }

    public function remove(Product $product): void
    {
        $this->productEntityRepository->removeAndFlush($product);
    }

    public function find(int $productId): ?Product
    {
        return $this->productEntityRepository->find($productId);
    }

    public function findVariant(int $variantId): ?ProductVariant
    {
        return $this->productVariantRepository->find($variantId);
    }

    /**
     * @throws Exception
     */
    public function getProductsByCategory(?int $number, int $categoryId, string $key): array
    {
        $number = $number ?: 1;

        return match (true) {
            'first' === $key => $this->productCategoryEntityRepository->findProductsByCategory($categoryId),
            'next' === $key  => $this->productCategoryEntityRepository->findProductsByCategory($categoryId, $number + 1),
            'prev' === $key  => $this->productCategoryEntityRepository->findProductsByCategory($categoryId, $number - 1),
        };
    }
}
