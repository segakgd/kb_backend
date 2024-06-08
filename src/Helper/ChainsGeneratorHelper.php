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

class ChainsGeneratorHelper
{
    public static function generate(JumpEnum $target): AbstractChain
    {
        return match ($target) {
            JumpEnum::refChain1 => new C1Chain(),
            JumpEnum::refChain2 => new C2Chain(),
            JumpEnum::refChain3 => new C3Chain(),
            JumpEnum::refChain4 => new C4Chain(),
            JumpEnum::refChain5 => new C5Chain(),
            JumpEnum::refChain6 => new C6Chain(),
            JumpEnum::refChain7 => new C7Chain(),
            JumpEnum::refChain8 => new C8Chain(),
            JumpEnum::refChain9 => new C9Chain(),
            JumpEnum::refChain10 => new C10Chain(),
        };
    }
}
