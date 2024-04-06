<?php

namespace App\Service\System\Handler\Chain\Items\Popular;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Helper\MessageHelper;
use App\Service\Admin\Ecommerce\Product\Service\ProductService;
use App\Service\System\Common\PaginateService;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;
use Exception;

class ShopProductsPopularChain extends AbstractChain
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
        $products = $this->productService->getPopularProducts(1, 'first');

        $this->paginateService->pug($contract, $products, $cacheDto->getEvent()->getData());

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        $contractMessage = MessageHelper::createContractMessage(
            'Не понимаю о чем вы...',
        );

        $contract->addMessage($contractMessage);

        return false;
    }

    public function validateCondition(string $content): bool
    {
        return $content === 'Популярные товары';
    }
}
