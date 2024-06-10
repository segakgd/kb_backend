<?php

namespace App\Helper;

use App\Enum\JumpEnum;
use App\Service\System\Core\Chains\Items\AbstractChain;
use App\Service\System\Core\Chains\Items\C10Chain;
use App\Service\System\Core\Chains\Items\C1Chain;
use App\Service\System\Core\Chains\Items\C2Chain;
use App\Service\System\Core\Chains\Items\C3Chain;
use App\Service\System\Core\Chains\Items\C4Chain;
use App\Service\System\Core\Chains\Items\C5Chain;
use App\Service\System\Core\Chains\Items\C6Chain;
use App\Service\System\Core\Chains\Items\C7Chain;
use App\Service\System\Core\Chains\Items\C8Chain;
use App\Service\System\Core\Chains\Items\C9Chain;
use App\Service\System\Core\Chains\Items\Ecommerce\common\ShopProductVariantChain;
use App\Service\System\Core\Chains\Items\Items\Cart\ContactViewChain;
use App\Service\System\Core\Chains\Items\Items\Cart\PhoneContactChain;
use App\Service\System\Core\Chains\Items\Items\Cart\Shipping\CartSaveChain;
use App\Service\System\Core\Chains\Items\Items\Cart\Shipping\ShippingApartmentChain;
use App\Service\System\Core\Chains\Items\Items\Cart\Shipping\ShippingCityChain;
use App\Service\System\Core\Chains\Items\Items\Cart\Shipping\ShippingCountryChain;
use App\Service\System\Core\Chains\Items\Items\Cart\Shipping\ShippingEntranceChain;
use App\Service\System\Core\Chains\Items\Items\Cart\Shipping\ShippingNumberHomeChain;
use App\Service\System\Core\Chains\Items\Items\Cart\Shipping\ShippingRegionChain;
use App\Service\System\Core\Chains\Items\Items\Cart\Shipping\ShippingStreetChain;
use App\Service\System\Core\Chains\Items\Items\Category\ShopProductsCategoryChain;
use App\Service\System\Core\Chains\Items\Items\Category\ShopProductsChain;
use App\Service\System\Core\Chains\Items\Items\Category\ShowShopProductsCategoryChain;
use App\Service\System\Core\Chains\Items\Items\FinalChain;
use App\Service\System\Core\Chains\Items\Items\Popular\ShopProductPopularChain;
use App\Service\System\Core\Chains\Items\Items\Popular\ShopProductsPopularChain;
use App\Service\System\Core\Chains\Items\Items\Promo\ShopProductPromoChain;
use App\Service\System\Core\Chains\Items\Items\Promo\ShopProductsPromoChain;

class ChainsGeneratorHelper
{
    public static function generate(JumpEnum $target): AbstractChain
    {
        $targetClass = match ($target) {
            JumpEnum::refChain1 => new C1Chain,
            JumpEnum::refChain2 => new C2Chain,
            JumpEnum::refChain3 => new C3Chain,
            JumpEnum::refChain4 => new C4Chain,
            JumpEnum::refChain5 => new C5Chain,
            JumpEnum::refChain6 => new C6Chain,
            JumpEnum::refChain7 => new C7Chain,
            JumpEnum::refChain8 => new C8Chain,
            JumpEnum::refChain9 => new C9Chain,
            JumpEnum::refChain10 => new C10Chain,

            // todo old
            JumpEnum::ShowShopProductsCategory => ShowShopProductsCategoryChain::class,
            JumpEnum::ShopProductsCategory => ShopProductsCategoryChain::class,
            JumpEnum::ShopProducts => ShopProductsChain::class,
            JumpEnum::ShopProductsPopular => ShopProductsPopularChain::class,
            JumpEnum::ShopProductPopular => ShopProductPopularChain::class,
            JumpEnum::ShopProductsPromo => ShopProductsPromoChain::class,
            JumpEnum::ShopProductPromo => ShopProductPromoChain::class,
            JumpEnum::ShopVariant, JumpEnum::ShopVariantCount => ShopProductVariantChain::class,
            JumpEnum::ShopFinal => FinalChain::class,
            JumpEnum::CartViewContact, JumpEnum::CartContact => ContactViewChain::class,
            JumpEnum::CartPhoneContact => PhoneContactChain::class,
            JumpEnum::CartShipping => CartSaveChain::class,
            JumpEnum::CartShippingCountry => ShippingCountryChain::class,
            JumpEnum::CartShippingRegion => ShippingRegionChain::class,
            JumpEnum::CartShippingCity => ShippingCityChain::class,
            JumpEnum::CartShippingStreet => ShippingStreetChain::class,
            JumpEnum::CartShippingNumberHome => ShippingNumberHomeChain::class,
            JumpEnum::CartShippingEntrance => ShippingEntranceChain::class,
            JumpEnum::CartShippingApartment => ShippingApartmentChain::class,
            JumpEnum::CartSave => CartSaveChain::class,
            JumpEnum::CartFinish => FinalChain::class,
            JumpEnum::Main => throw new \Exception(),
            JumpEnum::Cart => throw new \Exception(),
        };

        return new $targetClass;
    }
}
