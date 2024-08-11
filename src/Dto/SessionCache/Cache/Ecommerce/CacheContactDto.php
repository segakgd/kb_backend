<?php

namespace App\Dto\SessionCache\Cache\Ecommerce;

use App\Doctrine\DoctrineMappingInterface;

class CacheContactDto implements DoctrineMappingInterface
{
    public static function fromArray(array $data): static
    {
        return new CacheContactDto();
    }

    public function toArray(): array
    {
        return [];
    }
}
