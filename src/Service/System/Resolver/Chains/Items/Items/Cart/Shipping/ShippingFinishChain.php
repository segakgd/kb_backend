<?php

namespace App\Service\System\Resolver\Chains\Items\Items\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Resolver\Dto\Contract;

class ShippingFinishChain // extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

        return true;
    }

    public function fall(Contract $contract, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}
