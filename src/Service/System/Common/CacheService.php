<?php

namespace App\Service\System\Common;

use App\Dto\Scenario\ScenarioChainDto;
use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Enum\JumpEnum;

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
            );
    }

    public static function enrichStepCache(array $stepChains, CacheEventDto $cacheEventDto): void
    {
        /** @var ScenarioChainDto $stepChain */
        foreach ($stepChains as $stepChain) {
            $chain = (new CacheChainDto)
                ->setTarget(JumpEnum::from($stepChain->getTarget()))
                ->setFinished($stepChain->isFinish())
                ->setRepeat(false);

            $cacheEventDto->addChain($chain);
        }
    }
}
