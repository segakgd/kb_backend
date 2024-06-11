<?php

namespace App\Helper;

use App\Enum\JumpEnum;
use App\Service\System\Constructor\Core\Chains\AbstractChain;
use App\Service\System\Constructor\Items\GreetingChain;
use App\Service\System\Constructor\Items\ProductCategoryChain;
use App\Service\System\Constructor\Items\ProductsByCategoryChain;
use App\Service\System\Constructor\Items\StartChain;
use App\Service\System\Constructor\Items\Test\C10Chain;
use App\Service\System\Constructor\Items\Test\C1Chain;
use App\Service\System\Constructor\Items\Test\C2Chain;
use App\Service\System\Constructor\Items\Test\C3Chain;
use App\Service\System\Constructor\Items\Test\C4Chain;
use App\Service\System\Constructor\Items\Test\C5Chain;
use App\Service\System\Constructor\Items\Test\C6Chain;
use App\Service\System\Constructor\Items\Test\C7Chain;
use App\Service\System\Constructor\Items\Test\C8Chain;
use App\Service\System\Constructor\Items\Test\C9Chain;
use Exception;

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

            JumpEnum::GreetingChain => new GreetingChain,
            JumpEnum::StartChain => new StartChain,
            JumpEnum::ProductCategoryChain => new ProductCategoryChain,
            JumpEnum::ProductsByCategoryChain => new ProductsByCategoryChain,

            JumpEnum::Main, JumpEnum::Cart => throw new Exception('Need add chain'),
        };

        return new $targetClass;
    }
}
