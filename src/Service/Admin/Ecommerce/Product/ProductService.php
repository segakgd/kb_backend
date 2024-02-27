<?php

namespace App\Service\Admin\Ecommerce\Product;

use App\Repository\Ecommerce\ProductEntityRepository;
use Exception;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductEntityRepository $entityRepository,
    ) {
    }

    // todo типизация

    /**
     * @throws Exception
     */
    public function getProductsByCategory($pageNow, $categoryName, $key): array // todo переделать в $categoryId (хранить id совместно с названием)
    {
        $paginator = [];

        if ('product.next' === $key) {
            $paginator = $this->entityRepository->findProductsByCategoryName($categoryName, $pageNow + 1);
        }

        if ('product.prev' === $key) {
            $paginator = $this->entityRepository->findProductsByCategoryName($categoryName, $pageNow - 1);
        }


        return $paginator;
    }
}
