<?php

namespace App\Service\System\Resolver\Chains\Items\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Resolver\Dto\Contract;

class FinalChain // extends AbstractChain
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
