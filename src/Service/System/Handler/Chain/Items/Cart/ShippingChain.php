<?php

namespace App\Service\System\Handler\Chain\Items\Cart;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Contract;
use App\Service\System\Handler\Chain\AbstractChain;

class ShippingChain extends AbstractChain
{
    public function success(Contract $contract, CacheDto $cacheDto): bool
    {
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
