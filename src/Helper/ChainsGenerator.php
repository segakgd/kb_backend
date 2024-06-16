<?php

namespace App\Helper;

use App\Enum\JumpEnum;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Items\GreetingChain;
use App\Service\Constructor\Items\CategoriesChain;
use App\Service\Constructor\Items\ProductsByCategoryChain;
use App\Service\Constructor\Items\StartChain;
use App\Service\Constructor\Items\VariantProductChain;
use App\Service\Constructor\Items\VariantsProductChain;
use Exception;

readonly class ChainsGenerator
{
    public function __construct(
        private ProductsByCategoryChain $productsByCategoryChain,
        private CategoriesChain         $productCategoryChain,
        private VariantsProductChain    $variantsProductChain,
        private VariantProductChain     $variantProductChain,
    ) {
    }

    /**
     * @throws Exception
     */
    public function generate(JumpEnum $target): AbstractChain
    {
        return match ($target) {
            JumpEnum::GreetingChain => new GreetingChain,
            JumpEnum::StartChain => new StartChain,
            JumpEnum::ProductCategoryChain => $this->productCategoryChain,
            JumpEnum::ProductsByCategoryChain => $this->productsByCategoryChain,
            JumpEnum::VariantsProductChain => $this->variantsProductChain,
            JumpEnum::VariantProductChain => $this->variantProductChain,

            JumpEnum::Main, JumpEnum::Cart => throw new Exception('Need add chain'),
        };
    }
}
