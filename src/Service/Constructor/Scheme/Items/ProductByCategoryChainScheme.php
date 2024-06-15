<?php

namespace App\Service\Constructor\Scheme\Items;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Service\Constructor\Scheme\MainScheme;

class ProductByCategoryChainScheme extends MainScheme
{
    /**
     * Need return chains scheme
     *
     * @return array<CacheChainDto>
     */
    public static function get(): array
    {
        return [
        ];
    }
}