<?php

namespace App\Service\System\Handler\Items\Sub;

use App\Service\System\Handler\Chain\ShopProductChain;
use App\Service\System\Handler\Chain\ShopProductsCategoryChain;
use App\Service\System\Handler\Chain\ShopProductsChain;
use App\Service\System\Handler\Chain\ShowShopProductsCategoryChain;
use App\Service\System\Handler\PreMessageDto;

class ChainHandler
{
    public function __construct(
        private readonly ShowShopProductsCategoryChain $showShopProductsCategoryChain,
        private readonly ShopProductsCategoryChain $shopProductsCategoryChain,
        private readonly ShopProductsChain $shopProductsChain,
        private readonly ShopProductChain $shopProductChain,
    ) {
    }

    public function handle(PreMessageDto $preMessageDto, array &$cache, string $content): PreMessageDto
    {
        $chains = $cache['event']['chains'];

        // todo подумай в рамках ооп, создай сущность которая будех зранить значения нунешнего шага и всё такое...

        foreach ($chains as $key => $chain) {
            if ($chain['finished'] === false) {
                $isHandle = $this->handleByType($chain['target'], $preMessageDto, $content);

                if (count($chains) === ($key + 1)) {
                    $cache['event']['status'] = 'finished';
                }

                if ($isHandle) {
                    $chains[$key]['finished'] = true;
                }

                break;
            }
        }

        $cache['event']['chains'] = $chains;


        return $preMessageDto;
    }

    private function handleByType(string $target, PreMessageDto $preMessageDto, ?string $content = null): bool
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
