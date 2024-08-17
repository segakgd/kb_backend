<?php

namespace App\Controller;

use PHPUnit\Util\Exception;

abstract class AbstractResponse
{
    public static function mapFromEntity(object $entity): static
    {
        throw new Exception('Method not realized!');
    }

    public static function mapFromArray(array $data): static
    {
        throw new Exception('Method not realized!');
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
