<?php

namespace App\Service\Admin\Ecommerce\Product\Service;

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
    ) {
    }

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
    public function getPopularProducts(?int $pageNow, string $key): array
    {
        $pageNow = $pageNow ?: 1;

        return match (true) {
            'first' === $key => $this->productEntityRepository->getPopularProducts(1),
            'next' === $key => $this->productEntityRepository->getPopularProducts($pageNow + 1),
            'prev' === $key => $this->productEntityRepository->getPopularProducts($pageNow - 1),
        };
    }

    /**
     * @throws Exception
     */
    public function getPromoProducts(?int $pageNow, string $key): array
    {
        $pageNow = $pageNow ?: 1;

        return match (true) {
            'first' === $key => $this->productEntityRepository->getPromoProducts(1),
            'next' === $key => $this->productEntityRepository->getPromoProducts($pageNow + 1),
            'prev' === $key => $this->productEntityRepository->getPromoProducts($pageNow - 1),
        };
    }

    /**
     * @throws Exception
     */
    public function getProductsByCategory(?int $pageNow, int $categoryId, string $key): array
    {
        $pageNow = $pageNow ?: 1;

        return match (true) {
            'product.first' === $key => $this->productCategoryEntityRepository->findProductsByCategory($categoryId, 1),
            'product.next' === $key => $this->productCategoryEntityRepository->findProductsByCategory($categoryId, $pageNow + 1),
            'product.prev' === $key => $this->productCategoryEntityRepository->findProductsByCategory($categoryId, $pageNow - 1),
        };
    }
}
