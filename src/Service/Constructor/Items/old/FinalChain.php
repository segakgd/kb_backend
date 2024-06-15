<?php

namespace App\Service\Constructor\Items\old;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\Constructor\Core\Dto\Responsible;

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
