<?php

namespace App\Service\System\Constructor\Items\old\Cart\Shipping;

use App\Dto\SessionCache\Cache\CacheDto;
use App\Service\System\Constructor\Core\Dto\Responsible;

class ShippingFinishChain // extends AbstractChain
{
    public function success(Responsible $responsible, CacheDto $cacheDto): bool
    {
        $content = $cacheDto->getContent();

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
