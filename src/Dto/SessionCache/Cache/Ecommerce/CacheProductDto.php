<?php

namespace App\Dto\SessionCache\Cache\Ecommerce;

use App\Dto\Common\AbstractDto;

class CacheProductDto extends AbstractDto
{
    public static function fromArray(array $data): AbstractDto
    {
        return new CacheProductDto;
    }

    public function toArray(): array
    {
        return [];
    }
}
