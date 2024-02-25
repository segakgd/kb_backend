<?php

namespace App\Service\System\Handler;

use App\Service\System\Handler\Chain\ShopProductChain;
use App\Service\System\Handler\Chain\ShopProductsCategoryChain;
use App\Service\System\Handler\Chain\ShopProductsChain;
use App\Service\System\Handler\Chain\ShowShopProductsCategoryChain;

class ChainHandler
{
    public function __construct(
        private readonly ShowShopProductsCategoryChain $showShopProductsCategoryChain,
        private readonly ShopProductsCategoryChain $shopProductsCategoryChain,
        private readonly ShopProductsChain $shopProductsChain,
        private readonly ShopProductChain $shopProductChain,
    ) {
    }

    public function handleByType(string $target, PreMessageDto $preMessageDto, ?string $content = null): bool
    {
        return match ($target) {
            'show.shop.products.category' => $this->showShopProductsCategoryChain->handle($preMessageDto),
            'shop.products.category' => $this->shopProductsCategoryChain->handle($preMessageDto, $content),
            'shop.products' => $this->shopProductsChain->handle($preMessageDto, $content),
            'shop.product' => $this->shopProductChain->handle(),
            default => '',
        };
    }
}
