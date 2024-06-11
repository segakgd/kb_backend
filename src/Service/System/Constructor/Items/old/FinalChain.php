<?php

namespace App\Service\System\Constructor\Items\old;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Constructor\Core\Dto\Responsible;

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
