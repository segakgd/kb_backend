<?php

namespace App\Service\Admin\Ecommerce\Product;

use App\Entity\Ecommerce\Product;
use App\Entity\Ecommerce\ProductVariant;
use App\Repository\Ecommerce\ProductCategoryEntityRepository;
use App\Repository\Ecommerce\ProductEntityRepository;
use App\Repository\Ecommerce\ProductVariantRepository;
use Exception;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductEntityRepository $productEntityRepository,
        private readonly ProductCategoryEntityRepository $productCategoryEntityRepository,
        private readonly ProductVariantRepository $productVariantRepository,
    ) {
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
    public function getProductsByCategory(?int $pageNow, int $categoryId, string $key): array // todo переделать в $categoryId (хранить id совместно с названием)
    {
        $pageNow = $pageNow ?: 1;

        return match (true) {
            'product.first' === $key => $this->productCategoryEntityRepository->findProductsByCategory($categoryId, 1),
            'product.next' === $key => $this->productCategoryEntityRepository->findProductsByCategory($categoryId, $pageNow + 1),
            'product.prev' === $key => $this->productCategoryEntityRepository->findProductsByCategory($categoryId, $pageNow - 1),
        };
    }
}
