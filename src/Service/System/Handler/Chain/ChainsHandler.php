<?php

namespace App\Service\System\Handler\Chain;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\GotoChainsEnum;
use App\Service\System\Handler\Chain\Items\Cart\ContactChain;
use App\Service\System\Handler\Chain\Items\Cart\ContactViewChain;
use App\Service\System\Handler\Chain\Items\Cart\PhoneContactChain;
use App\Service\System\Handler\Chain\Items\Cart\Shipping\CartSaveChain;
use App\Service\System\Handler\Chain\Items\Cart\Shipping\ShippingApartmentChain;
use App\Service\System\Handler\Chain\Items\Cart\Shipping\ShippingChain;
use App\Service\System\Handler\Chain\Items\Cart\Shipping\ShippingCityChain;
use App\Service\System\Handler\Chain\Items\Cart\Shipping\ShippingCountryChain;
use App\Service\System\Handler\Chain\Items\Cart\Shipping\ShippingEntranceChain;
use App\Service\System\Handler\Chain\Items\Cart\Shipping\ShippingFinishChain;
use App\Service\System\Handler\Chain\Items\Cart\Shipping\ShippingNumberHomeChain;
use App\Service\System\Handler\Chain\Items\Cart\Shipping\ShippingRegionChain;
use App\Service\System\Handler\Chain\Items\Cart\Shipping\ShippingStreetChain;
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
use App\Service\System\Resolver\Dto\Contract;
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

        private readonly ContactViewChain $contactViewChain,
        private readonly ContactChain $contactChain,
        private readonly PhoneContactChain $phoneContactChain,
        private readonly ShippingChain $shippingChain,

        private readonly ShippingApartmentChain $shippingApartmentChain,
        private readonly ShippingStreetChain $shippingStreetChain,
        private readonly ShippingRegionChain $shippingRegionChain,
        private readonly ShippingNumberHomeChain $shippingNumberHomeChain,
        private readonly ShippingEntranceChain $shippingEntranceChain,
        private readonly ShippingCountryChain $shippingCountryChain,
        private readonly ShippingCityChain $shippingCityChain,
        private readonly CartSaveChain $cartSaveChain,
        private readonly ShippingFinishChain $shippingFinishChain,
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(Contract $contract, CacheDto $cacheDto): Contract
    {
        $chains = $cacheDto->getEvent()->getChains();

        // todo подумай в рамках ооп, создай сущность которая будех зранить значения нунешнего шага и всё такое...

        $chainCount = count($chains);

        foreach ($chains as $key => $chain) {
            /** @var CacheChainDto $chain */

            if ($chain->isNotFinished()) {
                $isHandle = $this->handleByType($chain->getTarget(), $contract, $cacheDto);

                if ($contract->getGoto() !== null) {
                    break;
                }

                if ($isHandle) {
                    $chain->setFinished(true);
                }

                if ($chainCount === 1 + $key && $chain->isFinished()) {
                    $cacheDto->getEvent()->setFinished(true);
                }

                break;
            }
        }

        $cacheDto->getEvent()->setChains($chains); // todo зачем? там же внутри объекты, это же ссылки

        return $contract;
    }

    /**
     * @throws Exception
     */
    private function handleByType(GotoChainsEnum $target, Contract $contract, CacheDto $cacheDto): bool
    {
        $chain = match ($target) {
            // категории
            GotoChainsEnum::ShowShopProductsCategory => $this->showShopProductsCategoryChain, // старт цепи, вывод доступных категорий. выбрать категории
            GotoChainsEnum::ShopProductsCategory => $this->shopProductsCategoryChain, // показывам товар по выбранной категории
            GotoChainsEnum::ShopProducts => $this->shopProductsChain, // прокрутка товров, пагинация, выбор

            // Популярные
            GotoChainsEnum::ShopProductsPopular => $this->shopProductsPopularChain, // старт цепи, вывод популярные товары
            GotoChainsEnum::ShopProductPopular => $this->shopProductPopularChain, // прокрутка товров, пагинация, выбор

            // Акционные
            GotoChainsEnum::ShopProductsPromo => $this->shopProductsPromoChain, // старт цепи, вывод акционные товары
            GotoChainsEnum::ShopProductPromo => $this->shopProductPromoChain, // прокрутка товров, пагинация, выбор

            // Общее
            GotoChainsEnum::ShopVariant => $this->productVariantChain, // выбор варианта, предлагаем выбрать количество
            GotoChainsEnum::ShopVariantCount => $this->variantCount, // выбор количества, выводим финальное сообщение, что в корзину добавлен такой-то такой-то товар
            GotoChainsEnum::ShopFinal => $this->finalChain, // финальная заглушка, для обработки навигации

            // Овормление зачки
            GotoChainsEnum::CartViewContact => $this->contactViewChain,
            GotoChainsEnum::CartPhoneContact => $this->phoneContactChain,
            GotoChainsEnum::CartContact => $this->contactChain,
            GotoChainsEnum::CartShipping => $this->shippingChain,

            GotoChainsEnum::CartShippingCountry => $this->shippingCountryChain,
            GotoChainsEnum::CartShippingRegion => $this->shippingRegionChain,
            GotoChainsEnum::CartShippingCity => $this->shippingCityChain,
            GotoChainsEnum::CartShippingStreet => $this->shippingStreetChain,
            GotoChainsEnum::CartShippingNumberHome => $this->shippingNumberHomeChain,
            GotoChainsEnum::CartShippingEntrance => $this->shippingEntranceChain,
            GotoChainsEnum::CartShippingApartment => $this->shippingApartmentChain,
            GotoChainsEnum::CartSave => $this->cartSaveChain,
            GotoChainsEnum::CartFinish => $this->shippingFinishChain,

            // todo не указал цену доставки
            // todo после оформления чистим корзину

            // todo изменить контакты - реализовать
            // todo изменить доставку - реализовать
            // todo изменить продукты - реализовать
            // todo удалить заказ - реализовать
            // todo олпатить - реализовать
            // todo показывать меню по условиям.

            // todo добавить возможность просматривать информацию о заказе (статус, история, оплаты)
            // todo добавить возможность выбирать какие поля нужны
        };

        return $chain->chain($contract, $cacheDto);
    }
}
