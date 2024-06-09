<?php

namespace App\Service\System\Core\Chains\Items\Items;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Core\Dto\Responsible;

class FinalChain // extends AbstractChain
{

    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        return true;
    }

    public function fall(Responsible $responsible, CacheDto $cacheDto): bool
    {
        return false;
    }

    public function validateCondition(string $content): bool
    {
        return true;
    }
}
