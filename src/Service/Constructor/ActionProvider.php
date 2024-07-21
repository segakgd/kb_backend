<?php

namespace App\Service\Constructor;

use App\Enum\TargetEnum;
use App\Service\Constructor\Actions\Cart\CartFinishChain;
use App\Service\Constructor\Actions\Cart\CartStartChain;
use App\Service\Constructor\Actions\Ecommerce\CategoriesChain;
use App\Service\Constructor\Actions\Ecommerce\ProductsByCategoryChain;
use App\Service\Constructor\Actions\Ecommerce\StartChain;
use App\Service\Constructor\Actions\Ecommerce\VariantProductChain;
use App\Service\Constructor\Actions\Ecommerce\VariantsProductChain;
use App\Service\Constructor\Actions\FinishChain;
use App\Service\Constructor\Actions\Order\OrderContactsFullNameChain;
use App\Service\Constructor\Actions\Order\OrderContactsPhoneChain;
use App\Service\Constructor\Actions\Order\OrderFinishChain;
use App\Service\Constructor\Actions\Order\OrderGreetingChain;
use App\Service\Constructor\Actions\Order\OrderShippingChain;
use App\Service\Constructor\Actions\Order\OrderShippingSwitch;
use App\Service\Constructor\Core\Chains\AbstractChain;
use Exception;

readonly class ActionProvider
{
    public function __construct(
        private ProductsByCategoryChain $productsByCategoryChain,
        private CategoriesChain $productCategoryChain,
        private VariantsProductChain $variantsProductChain,
        private VariantProductChain $variantProductChain,
    ) {}

    /**
     * @throws Exception
     */
    public function getByTarget(TargetEnum $target): AbstractChain
    {
        return match ($target) {
            TargetEnum::StartChain              => new StartChain(),
            TargetEnum::ProductCategoryChain    => $this->productCategoryChain,
            TargetEnum::ProductsByCategoryChain => $this->productsByCategoryChain,
            TargetEnum::VariantsProductChain    => $this->variantsProductChain,
            TargetEnum::VariantProductChain     => $this->variantProductChain,
            TargetEnum::FinishChain             => new FinishChain(),

            TargetEnum::OrderGreetingChain         => new OrderGreetingChain(),
            TargetEnum::OrderContactsFullNameChain => new OrderContactsFullNameChain(),
            TargetEnum::OrderContactsPhoneChain    => new OrderContactsPhoneChain(),
            TargetEnum::OrderShippingSwitch        => new OrderShippingSwitch(),
            TargetEnum::OrderShippingChain         => new OrderShippingChain(),
            TargetEnum::OrderFinishChain           => new OrderFinishChain(),

            TargetEnum::CartFinishChain => new CartFinishChain(),
            TargetEnum::CartStartChain  => new CartStartChain(),

            TargetEnum::Cart => null,
            TargetEnum::Main => null,
        };
    }
}
