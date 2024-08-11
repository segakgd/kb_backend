<?php

namespace App\Dto\SessionCache\Cache\Ecommerce;

use App\Doctrine\DoctrineMappingInterface;

class CacheShippingDto implements DoctrineMappingInterface
{
    public static function fromArray(array $data): static
    {
        return new CacheShippingDto();
    }

    public function toArray(): array
    {
        return [];
    }
}
