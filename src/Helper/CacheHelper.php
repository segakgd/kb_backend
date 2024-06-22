<?php

namespace App\Helper;

use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheEventDto;

class CacheHelper
{
    public static function createCacheEventDto(): CacheEventDto
    {
        return (new CacheEventDto())
            ->setFinished(false)
            ->setData(
                (new CacheDataDto())
                    ->setProductId(null)
                    ->setPageNow(null)
            );
    }
}
