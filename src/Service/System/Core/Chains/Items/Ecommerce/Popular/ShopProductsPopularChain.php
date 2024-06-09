<?php

namespace App\Service\System\Core\Chains\Items\Items\Popular;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Core\Dto\Responsible;
use Exception;

class ShopProductsPopularChain // extends AbstractChain
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $products = $this->productService->getPopularProducts(1, 'first');

        $this->paginateService->pug($responsible, $products, $cacheDto->getEvent()->getData());

        return true;
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $responsibleMessage = MessageHelper::createResponsibleMessage(
            'Не понимаю о чем вы...',
        );

        $responsible->getResult()->addMessage($responsibleMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        return $content === 'Популярные товары';
    }
}
