<?php

namespace App\Dto\SessionCache\Cache\Ecommerce;

use App\Dto\Common\AbstractDto;

class CacheContactDto extends AbstractDto
{
    public static function fromArray(array $data): AbstractDto
    {
        return new CacheContactDto;
    }

    public function toArray(): array
    {
        return [];
    }
}
