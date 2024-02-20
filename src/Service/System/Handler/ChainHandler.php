<?php

namespace App\Service\System\Handler;

use App\Dto\Core\Telegram\Request\Message\MessageDto;
use App\Service\System\Handler\Chain\ShopProductChain;
use App\Service\System\Handler\Chain\ShopProductsCategoryChain;
use App\Service\System\Handler\Chain\ShopProductsChain;

class ChainHandler
{
    public function __construct(
        private readonly ShopProductsCategoryChain $shopProductsCategoryChain,
        private readonly ShopProductsChain $shopProductsChain,
        private readonly ShopProductChain $shopProductChain,
    ) {
    }

    public function handleByType(string $target, string $action, MessageDto $messageDto, ?string $content = null): void
    {
        match ($target){
            'shop.products.category' => $this->shopProductsCategoryChain->handle($action, $messageDto, $content),
            'shop.products' => $this->shopProductsChain->handle(),
            'shop.product' => $this->shopProductChain->handle(),
            default => '',
        };
    }
}
