<?php

namespace App\Helper;

use App\Enum\JumpEnum;
use App\Service\Constructor\Core\Chains\AbstractChain;
use App\Service\Constructor\Items\GreetingChain;
use App\Service\Constructor\Items\ProductCategoryChain;
use App\Service\Constructor\Items\ProductsByCategoryChain;
use App\Service\Constructor\Items\StartChain;
use App\Service\Constructor\Items\Test\C10Chain;
use App\Service\Constructor\Items\Test\C1Chain;
use App\Service\Constructor\Items\Test\C2Chain;
use App\Service\Constructor\Items\Test\C3Chain;
use App\Service\Constructor\Items\Test\C4Chain;
use App\Service\Constructor\Items\Test\C5Chain;
use App\Service\Constructor\Items\Test\C6Chain;
use App\Service\Constructor\Items\Test\C7Chain;
use App\Service\Constructor\Items\Test\C8Chain;
use App\Service\Constructor\Items\Test\C9Chain;
use Exception;

readonly class ChainsGenerator
{
    public function __construct(
        private ProductsByCategoryChain $productsByCategoryChain,
        private ProductCategoryChain $productCategoryChain,
    ) {
    }

    /**
     * @throws Exception
     */
    public function generate(JumpEnum $target): AbstractChain
    {
        return match ($target) {
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

            JumpEnum::GreetingChain => new GreetingChain,
            JumpEnum::StartChain => new StartChain,
            JumpEnum::ProductCategoryChain => $this->productCategoryChain,
            JumpEnum::ProductsByCategoryChain => $this->productsByCategoryChain,

            JumpEnum::Main, JumpEnum::Cart => throw new Exception('Need add chain'),
        };
    }
}
