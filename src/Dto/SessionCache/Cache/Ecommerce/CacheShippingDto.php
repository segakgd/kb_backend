<?php

namespace App\Dto\SessionCache\Cache\Ecommerce;

use App\Dto\Common\AbstractDto;

class CacheShippingDto extends AbstractDto
{
    public static function fromArray(array $data): AbstractDto
    {
        return (new CacheShippingDto);
    }

    public function toArray(): array
    {
        return [];
    }
}
