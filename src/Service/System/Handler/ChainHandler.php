<?php

namespace App\Service\System\Handler;

use App\Dto\Core\Telegram\Request\Message\MessageDto;
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

    public function handleByType(string $target, MessageDto $messageDto, ?string $content = null): bool
    {
//        dd($target);
        return match ($target) {
            'show.shop.products.category' => $this->showShopProductsCategoryChain->handle($messageDto),
            'shop.products.category' => $this->shopProductsCategoryChain->handle($messageDto, $content),
            'shop.products' => $this->shopProductsChain->handle($messageDto, $content),
            'shop.product' => $this->shopProductChain->handle(),
            default => '',
        };
    }
}
