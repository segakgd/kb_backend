<?php

namespace App\Service\System\Handler\Items\Sub;

use App\Service\System\Handler\Chain\ShopProductChain;
use App\Service\System\Handler\Chain\ShopProductsCategoryChain;
use App\Service\System\Handler\Chain\ShopProductsChain;
use App\Service\System\Handler\Chain\ShowShopProductsCategoryChain;
use App\Service\System\Handler\Contract;
use App\Service\System\Handler\Dto\Cache\CacheDto;
use Exception;

class ChainHandler
{
    public function __construct(
        private readonly ShowShopProductsCategoryChain $showShopProductsCategoryChain,
        private readonly ShopProductsCategoryChain $shopProductsCategoryChain,
        private readonly ShopProductsChain $shopProductsChain,
        private readonly ShopProductChain $shopProductChain,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(Contract $contract, array &$cache, string $content, CacheDto $cacheDto): Contract
    {
        $chains = $cache['event']['chains'];

        // todo подумай в рамках ооп, создай сущность которая будех зранить значения нунешнего шага и всё такое...

        foreach ($chains as $key => $chain) {
            if ($chain['finished'] === false) {
                $isHandle = $this->handleByType($chain['target'], $contract, $cacheDto, $content);

                if (count($chains) === ($key + 1)) {
                    $cache['event']['status'] = 'finished';
                }

                if ($isHandle) {
                    $chains[$key]['finished'] = true;
                }

                $goto = $contract->getGoto();

                if ($goto === Contract::GOTO_NEXT) {
                    $chains[$key]['finished'] = true;

                    continue;
                }

                break;
            }
        }

        $cache['event']['chains'] = $chains;

        return $contract;
    }

    /**
     * @throws Exception
     */
    private function handleByType(string $target, Contract $contract, CacheDto $cacheDto, ?string $content = null): bool
    {
        return match ($target) {
            'show.shop.products.category' => $this->showShopProductsCategoryChain->handle($contract),
            'shop.products.category' => $this->shopProductsCategoryChain->handle($contract, $content),
            'shop.products' => $this->shopProductsChain->handle($contract, $cacheDto),
            'shop.product' => $this->shopProductChain->handle(),
            default => '',
        };
    }
}
