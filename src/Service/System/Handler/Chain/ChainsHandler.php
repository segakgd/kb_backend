<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\ChainsEnum;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\Items\Category\ShopProductsCategoryChain;
use App\Service\System\Handler\Chain\Items\Category\ShopProductsChain;
use App\Service\System\Handler\Chain\Items\Category\ShowShopProductsCategoryChain;
use App\Service\System\Handler\Chain\Items\FinalChain;
use App\Service\System\Handler\Chain\Items\Popular\ShopProductPopularChain;
use App\Service\System\Handler\Chain\Items\Popular\ShopProductsPopularChain;
use App\Service\System\Handler\Chain\Items\Promo\ShopProductPromoChain;
use App\Service\System\Handler\Chain\Items\Promo\ShopProductsPromoChain;
use App\Service\System\Handler\Chain\Items\ShopProductVariantChain;
use App\Service\System\Handler\Chain\Items\VariantCount;
use Exception;

class ChainsHandler
{
    public function __construct(
        private readonly ShowShopProductsCategoryChain $showShopProductsCategoryChain,
        private readonly ShopProductsCategoryChain $shopProductsCategoryChain,
        private readonly ShopProductsChain $shopProductsChain,
        private readonly ShopProductVariantChain $productVariantChain,
        private readonly VariantCount $variantCount,
        private readonly FinalChain $finalChain,
        private readonly ShopProductsPopularChain $shopProductsPopularChain,
        private readonly ShopProductPopularChain $shopProductPopularChain,
        private readonly ShopProductPromoChain $shopProductPromoChain,
        private readonly ShopProductsPromoChain $shopProductsPromoChain,
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
            // категории
            ChainsEnum::ShowShopProductsCategory => $this->showShopProductsCategoryChain, // старт цепи, вывод доступных категорий. выбрать категории
            ChainsEnum::ShopProductsCategory => $this->shopProductsCategoryChain, // показывам товар по выбранной категории
            ChainsEnum::ShopProducts => $this->shopProductsChain, // прокрутка товров, пагинация, выбор

            // Популярные
            ChainsEnum::ShopProductsPopular => $this->shopProductsPopularChain, // старт цепи, вывод популярные товары
            ChainsEnum::ShopProductPopular => $this->shopProductPopularChain, // прокрутка товров, пагинация, выбор

            // Акционные
            ChainsEnum::ShopProductsPromo => $this->shopProductsPromoChain, // старт цепи, вывод акционные товары
            ChainsEnum::ShopProductPromo => $this->shopProductPromoChain, // прокрутка товров, пагинация, выбор

            // Общее
            ChainsEnum::ShopVariant => $this->productVariantChain, // выбор варианта, предлагаем выбрать количество
            ChainsEnum::ShopVariantCount => $this->variantCount, // выбор количества, выводим финальное сообщение, что в корзину добавлен такой-то такой-то товар
            ChainsEnum::ShopFinal => $this->finalChain, // финальная заглушка, для обработки навигации
        };

        return $chain->chain($contract, $cacheDto);
    }
}
