<?php

namespace App\Controller;

abstract class AbstractResponse
{
    public static function mapFromEntity(object $entity): static
    {
        return new static();
    }

    public static function mapFromArray(array $data): static
    {
        return new static();
    }

    public static function mapCollection(iterable $collection): array
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

    public static function mapCollectionArray(iterable $collection): array
    {
        $mapResult = [];

        foreach ($collection as $item) {
            if (!is_array($item)) {
                continue;
            }

            $mapResult = self::mapFromArray($item);
        }

        return $mapResult;
    }
}
