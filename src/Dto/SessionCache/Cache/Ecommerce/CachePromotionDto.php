<?php

namespace App\Dto\SessionCache\Cache\Ecommerce;

use App\Doctrine\DoctrineMappingInterface;

class CachePromotionDto implements DoctrineMappingInterface
{
    public static function fromArray(array $data): static
    {
        return new CachePromotionDto();
    }

    public function toArray(): array
    {
        return [];
    }
}
