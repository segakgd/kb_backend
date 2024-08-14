<?php

namespace App\Controller;

abstract class AbstractResponse
{
    public static function mapFromEntity(object $entity): static
    {
        return new static();
    }

    public static function mapFromCollection(iterable $collection): array
    {
        $mapResult = [];

        foreach ($collection as $item) {
            if (!is_object($item)) {
                continue;
            }

            $mapResult = self::mapFromEntity($item);
        }

        return $mapResult;
    }
}
