<?php

namespace App\Service\System\Constructor\Scheme\Items;

use App\Dto\SessionCache\Cache\CacheChainDto;
use App\Service\System\Constructor\Scheme\MainScheme;

class PromoProductChainScheme extends MainScheme
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