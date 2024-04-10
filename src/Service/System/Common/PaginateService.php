<?php

namespace App\Service\System\Common;

use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Entity\Ecommerce\Product;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Resolver\Dto\Contract;
use Exception;

/**
 * @deprecated need refactoring
 */
class PaginateService
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function first(Contract $contract, CacheDataDto $cacheDataDto): bool
    {
        $products = $this->productService->getProductsByCategory(
            $cacheDataDto->getPageNow(),
            $cacheDataDto->getCategoryId(),
            'product.first'
        );

        return $this->pug($contract, $products, $cacheDataDto);
    }

    /**
     * @throws Exception
     */
    public function prev(Contract $contract, CacheDataDto $cacheDataDto): bool
    {
        $products = $this->productService->getProductsByCategory(
            $cacheDataDto->getPageNow(),
            $cacheDataDto->getCategoryId(),
            'product.prev'
        );

        return $this->pug($contract, $products, $cacheDataDto);
    }

    /**
     * @throws Exception
     */
    public function next(Contract $contract, CacheDataDto $cacheDataDto): bool
    {
        $products = $this->productService->getProductsByCategory(
            $cacheDataDto->getPageNow(),
            $cacheDataDto->getCategoryId(),
            'product.next'
        );

        return $this->pug($contract, $products, $cacheDataDto);
    }

    public function pug(Contract $contract, array $products, CacheDataDto $cacheDataDto): bool
    {
        /** @var Product $product */
        $product = $products['items'][0];

        $contractMessage = MessageHelper::createContractMessage('');

        $cacheDataDto->setPageNow($products['paginate']['now']);
        $cacheDataDto->setProductId($product->getId());

        $message = MessageHelper::renderProductMessage(
            $product,
            $products['paginate']['now'],
            $products['paginate']['total'],
        );

        $contractMessage->setMessage($message);
        $contractMessage->setPhoto($product->getMainImage());

        if ($products['paginate']['next'] === null) {
            $replyMarkups = KeyboardHelper::getProductNav(['prev' => true]);
        } else if ($products['paginate']['prev'] === null) {
            $replyMarkups = KeyboardHelper::getProductNav(['next' => true]);
        } else {
            $replyMarkups = KeyboardHelper::getProductNav();
        }

        $contractMessage->setKeyBoard($replyMarkups);

        $contract->getResult()->addMessage($contractMessage);

        return false;
    }
}
