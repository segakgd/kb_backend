<?php

namespace App\Dto\SessionCache\Cache\Ecommerce;

use App\Dto\Common\AbstractDto;

class CachePromotionDto extends AbstractDto
{
    public static function fromArray(array $data): AbstractDto
    {
        return new CachePromotionDto;
    }

    public function toArray(): array
    {
        return [];
    }
}
