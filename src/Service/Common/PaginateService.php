<?php

namespace App\Service\Common;

use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Entity\Ecommerce\Product;
use App\Helper\KeyboardHelper;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\Constructor\Core\Dto\Responsible;
use Exception;

/**
 * @deprecated need refactoring
 */
readonly class PaginateService
{
    public function __construct(
        private ProductService $productService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function first(Responsible $responsible, CacheDataDto $cacheDataDto): bool
    {
        $products = $this->productService->getProductsByCategory(
            $cacheDataDto->getPageNow(),
            $cacheDataDto->getCategoryId(),
            'first'
        );

        return $this->pug($responsible, $products, $cacheDataDto);
    }

    /**
     * @throws Exception
     */
    public function prev(Responsible $responsible, CacheDataDto $cacheDataDto): bool
    {
        $products = $this->productService->getProductsByCategory(
            $cacheDataDto->getPageNow(),
            $cacheDataDto->getCategoryId(),
            'prev'
        );

        return $this->pug($responsible, $products, $cacheDataDto);
    }

    /**
     * @throws Exception
     */
    public function next(Responsible $responsible, CacheDataDto $cacheDataDto): bool
    {
        $products = $this->productService->getProductsByCategory(
            $cacheDataDto->getPageNow(),
            $cacheDataDto->getCategoryId(),
            'next'
        );

        return $this->pug($responsible, $products, $cacheDataDto);
    }

    public function pug(Responsible $responsible, array $products, CacheDataDto $cacheDataDto): bool
    {
        /** @var Product $product */
        $product = $products['items'][0];

        $responsibleMessage = MessageHelper::createResponsibleMessage('');

        $cacheDataDto->setPageNow($products['paginate']['now']);
        $cacheDataDto->setProductId($product->getId());

        $message = MessageHelper::renderProductMessage(
            $product,
            $products['paginate']['now'],
            $products['paginate']['total'],
        );

        $responsibleMessage->setMessage($message);
        $responsibleMessage->setPhoto($product->getMainImage());

        if ($products['paginate']['next'] === null) {
            $replyMarkups = KeyboardHelper::getProductNav(['prev' => true]);
        } else if ($products['paginate']['prev'] === null) {
            $replyMarkups = KeyboardHelper::getProductNav(['next' => true]);
        } else {
            $replyMarkups = KeyboardHelper::getProductNav();
        }

        $responsibleMessage->setKeyBoard($replyMarkups);

        $responsible->getResult()->setMessage($responsibleMessage);

        return false;
    }
}
