<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\ChainsEnum;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\Items\End;
use App\Service\System\Handler\Chain\Items\ShopProductsCategoryChain;
use App\Service\System\Handler\Chain\Items\ShopProductsChain;
use App\Service\System\Handler\Chain\Items\ShopProductVariantChain;
use App\Service\System\Handler\Chain\Items\ShowShopProductsCategoryChain;
use App\Service\System\Handler\Chain\Items\VariantCount;
use Exception;

class ChainHandler
{
    public function __construct(
        private readonly ShowShopProductsCategoryChain $showShopProductsCategoryChain,
        private readonly ShopProductsCategoryChain $shopProductsCategoryChain, // выбор категории, вывод первого товара выбранной категории
        private readonly ShopProductsChain $shopProductsChain,
        private readonly ShopProductVariantChain $productVariantChain,
        private readonly VariantCount $variantCount,
        private readonly End $end,
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
                    $cacheDto->getEvent()->setFinished(true); // todo костыль

                    if (!$chainsSub->isFinished()) {
                        $cacheDto->getEvent()->setFinished(false);

                        break;
                    }
                }
                // todo костыль <<<

                break;
            }
        }

        $cacheDto->getEvent()->setChains($chains); // todo зачем? там же внутри объекты, это же ссылки

        return $contract;
    }

    /**
     * @throws Exception
     */
    private function handleByType(ChainsEnum $target, Contract $contract, CacheDto $cacheDto): bool
    {
        $chain = match ($target) {
            ChainsEnum::ShowShopProductsCategory => $this->showShopProductsCategoryChain,
            ChainsEnum::ShopProductsCategory => $this->shopProductsCategoryChain,
            ChainsEnum::ShopProducts => $this->shopProductsChain,
            ChainsEnum::ShopVariant => $this->productVariantChain,
            ChainsEnum::ShopVariantCount => $this->variantCount,
            ChainsEnum::ShopVariantAdd => $this->end,
            ChainsEnum::ShopVariantFinal => throw new Exception(ChainsEnum::ShopVariantFinal->value . ' is not implementation'),
        };

        return $chain->chain($contract, $cacheDto);
    }
}
