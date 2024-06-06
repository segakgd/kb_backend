<?php

namespace App\Service\System\Common;

use App\Dto\Scenario\ScenarioChainDto;
use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Dto\SessionCache\Cache\CacheContractDto;
use App\Enum\JumpEnum;

class CacheService
{
    public static function createCacheEventDto(): CacheEventDto
    {
        return (new CacheEventDto())
            ->setFinished(false)
            ->setData(
                (new CacheDataDto)
                    ->setProductId(null)
                    ->setPageNow(null)
            );
    }

    public static function enrichContractCache(array $chains, CacheContractDto $cacheContractDto): void
    {
        /** @var ScenarioChainDto $chain */
        foreach ($chains as $chain) {
            if (is_array($chain)) {
                $chain = ScenarioChainDto::fromArray($chain);
            }

            $chain = (new CacheChainDto)
                ->setTarget(JumpEnum::from($chain->getTarget()))
                ->setFinished($chain->isFinish())
                ->setRepeat(false);

            $cacheContractDto->addChain($chain);
        }
    }
}
