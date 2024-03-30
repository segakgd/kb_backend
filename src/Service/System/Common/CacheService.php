<?php

namespace App\Service\System\Common;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Enum\GotoChainsEnum;

class CacheService
{
    public static function createCacheEventDto(): CacheEventDto
    {
        return (new CacheEventDto())
            ->setFinished(false)
            ->setChains([])
            ->setData(
                (new CacheDataDto)
                    ->setProductId(null)
                    ->setPageNow(null)
            )
        ;
    }

    public static function enrichStepCache(array $stepChains, CacheDto $cacheDto): void
    {
        foreach ($stepChains as $stepChain) {
            $chain = (new CacheChainDto)
                ->setTarget(GotoChainsEnum::from($stepChain['target']))
                ->setFinished($stepChain['finish'])
                ->setRepeat(false);

            $cacheDto->getEvent()->addChain($chain);
        }
    }
}
