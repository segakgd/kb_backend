<?php

namespace App\Service\System\Resolver\Chains\Items\Items\Promo;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Resolver\Dto\Contract;
use Exception;

class ShopProductsPromoChain // extends AbstractChain
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly PaginateService $paginateService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $products = $this->productService->getPromoProducts(1, 'first');

        $this->paginateService->pug($contract, $products, $cacheDto->getEvent()->getData());

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        $contractMessage = MessageHelper::createContractMessage(
            'Не понимаю о чем вы...',
        );

        $contract->getResult()->addMessage($contractMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        return $content === 'Акционные товары';
    }
}
