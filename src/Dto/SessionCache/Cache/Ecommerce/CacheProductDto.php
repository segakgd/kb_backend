<?php

namespace App\Dto\SessionCache\Cache\Ecommerce;

use App\Doctrine\DoctrineMappingInterface;

class CacheProductDto implements DoctrineMappingInterface
{
    public static function fromArray(array $data): static
    {
        return new CacheProductDto();
    }

    public function toArray(): array
    {
        return [];
    }
}
