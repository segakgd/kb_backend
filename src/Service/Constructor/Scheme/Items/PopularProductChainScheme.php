<?php

namespace App\Service\Constructor\Scheme\Items;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Enum\TargetEnum;
use App\Service\Constructor\Scheme\MainScheme;

class PopularProductChainScheme extends MainScheme
{
    /**
     * Need return chains scheme
     *
     * @return array<CacheChainDto>
     */
    public static function get(): array
    {
        return [
            static::chain(TargetEnum::StartChain),
            static::chain(TargetEnum::ProductCategoryChain),
        ];
    }
}