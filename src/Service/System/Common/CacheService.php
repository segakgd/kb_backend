<?php

namespace App\Service\System\Common;

use App\Dto\Scenario\ScenarioChainDto;
use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Dto\SessionCache\Cache\CacheStepDto;
use App\Enum\JumpEnum;

class CacheService
{
    public static function createCacheEventDto(): CacheEventDto
    {
        return (new CacheEventDto())
            ->setFinished(false)
            ->setSteps([])
            ->setData(
                (new CacheDataDto)
                    ->setProductId(null)
                    ->setPageNow(null)
            );
    }

    public static function enrichStepCache(array $stepChains, CacheStepDto $cacheStepDto): void
    {
        /** @var ScenarioChainDto $stepChain */
        foreach ($stepChains as $stepChain) {
            if (is_array($stepChain)) {
                $stepChain = ScenarioChainDto::fromArray($stepChain);
            }

            $chain = (new CacheChainDto)
                ->setTarget(JumpEnum::from($stepChain->getTarget()))
                ->setFinished($stepChain->isFinish())
                ->setRepeat(false);

            $cacheStepDto->addChain($chain);
        }
    }
}
