<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\ChainsEnum;
use App\Service\System\Contract;
use App\Service\System\Handler\Items\ShopProductChain;
use App\Service\System\Handler\Items\ShopProductsCategoryChain;
use App\Service\System\Handler\Items\ShopProductsChain;
use App\Service\System\Handler\Items\ShowShopProductsCategoryChain;
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
    public function handle(Contract $contract, CacheDto $cacheDto): Contract
    {
        $chains = $cacheDto->getEvent()->getChains();

        // todo подумай в рамках ооп, создай сущность которая будех зранить значения нунешнего шага и всё такое...

        foreach ($chains as $chain) {
            /** @var CacheChainDto $chain */
            if ($chain->isNotFinished()) {
                $isHandle = $this->handleByType($chain->getTarget(), $contract, $cacheDto);

                if ($contract->getGoto() !== null) {
                    break;
                }

                if ($isHandle) {
                    $chain->setFinished(true);
                }

                // todo нужно ли обрабатывать $isHandle === false??

                // todo костыль >>>
                foreach ($chains as $chainsSub) {
                    /** @var CacheChainDto $chainsSub */

                    if (!$chainsSub->isFinished()) {
                        $cacheDto->getEvent()->setFinished(false);

                        break;
                    }
                }
                // todo костыль <<<

                break;
            }
        }

        $cacheDto->getEvent()->setChains($chains);

        return $contract;
    }

    /**
     * @throws Exception
     */
    private function handleByType(ChainsEnum $target, Contract $contract, CacheDto $cacheDto): bool
    {
        return match ($target) {
            ChainsEnum::ShowShopProductsCategory => $this->showShopProductsCategoryChain->handle($contract),
            ChainsEnum::ShopProductsCategory => $this->shopProductsCategoryChain->handle($contract, $cacheDto),
            ChainsEnum::ShopProducts => $this->shopProductsChain->handle($contract, $cacheDto),
            ChainsEnum::ShopProduct => $this->shopProductChain->handle(),
        };
    }
}
