<?php

namespace App\Helper;

use App\Enum\GotoChainsEnum;
use App\Service\System\Resolver\Chains\AbstractChain;
use App\Service\System\Resolver\Chains\Items\C10Chain;
use App\Service\System\Resolver\Chains\Items\C1Chain;
use App\Service\System\Resolver\Chains\Items\C2Chain;
use App\Service\System\Resolver\Chains\Items\C3Chain;
use App\Service\System\Resolver\Chains\Items\C4Chain;
use App\Service\System\Resolver\Chains\Items\C5Chain;
use App\Service\System\Resolver\Chains\Items\C6Chain;
use App\Service\System\Resolver\Chains\Items\C7Chain;
use App\Service\System\Resolver\Chains\Items\C8Chain;
use App\Service\System\Resolver\Chains\Items\C9Chain;

class ChainsGeneratorHelper
{
    public static function generate(GotoChainsEnum $target): AbstractChain
    {
        return match ($target) {
            GotoChainsEnum::refChain1 => new C1Chain(),
            GotoChainsEnum::refChain2 => new C2Chain(),
            GotoChainsEnum::refChain3 => new C3Chain(),
            GotoChainsEnum::refChain4 => new C4Chain(),
            GotoChainsEnum::refChain5 => new C5Chain(),
            GotoChainsEnum::refChain6 => new C6Chain(),
            GotoChainsEnum::refChain7 => new C7Chain(),
            GotoChainsEnum::refChain8 => new C8Chain(),
            GotoChainsEnum::refChain9 => new C9Chain(),
            GotoChainsEnum::refChain10 => new C10Chain(),
        };
    }
}
