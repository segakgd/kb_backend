<?php

namespace App\Service\Constructor;

use App\Enum\TargetEnum;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Items\CategoriesChain;
use App\Service\Constructor\Items\FinishChain;
use App\Service\Constructor\Items\GreetingChain;
use App\Service\Constructor\Items\ProductsByCategoryChain;
use App\Service\Constructor\Items\StartChain;
use App\Service\Constructor\Items\VariantProductChain;
use App\Service\Constructor\Items\VariantsProductChain;
use Exception;

readonly class ChainProvider
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
            TargetEnum::GreetingChain           => new GreetingChain(),
            TargetEnum::StartChain              => new StartChain(),
            TargetEnum::ProductCategoryChain    => $this->productCategoryChain,
            TargetEnum::ProductsByCategoryChain => $this->productsByCategoryChain,
            TargetEnum::VariantsProductChain    => $this->variantsProductChain,
            TargetEnum::VariantProductChain     => $this->variantProductChain,
            TargetEnum::FinishChain             => new FinishChain(),

            TargetEnum::Main, TargetEnum::Cart => throw new Exception('Need add chain'),
        };
    }
}
