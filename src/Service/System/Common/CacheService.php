<?php

namespace App\Service\System\Common;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Enum\ChainsEnum;

class CacheService
{


    public static function enrichStepCache(array $stepChains, CacheDto $cacheDto): void
    {
        foreach ($stepChains as $stepChain) {
            $chain = (new CacheChainDto)
                ->setTarget(ChainsEnum::from($stepChain['target']))
                ->setFinished($stepChain['finish']);

            $cacheDto->getEvent()->addChain($chain);
        }
    }
}
