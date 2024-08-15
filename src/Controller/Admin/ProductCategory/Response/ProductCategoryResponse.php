<?php

namespace App\Controller\Admin\ProductCategory\Response;

use App\Controller\AbstractResponse;
use App\Entity\Ecommerce\ProductCategory;
use Exception;

class ProductCategoryResponse extends AbstractResponse
{
    public string $id;

    public string $name;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof ProductCategory) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->id = $entity->getId();
        $response->name = $entity->getName();

        return $response;
    }

    /**
     * @throws Exception
     */
    public static function mapFromCollection(iterable $collection): array
    {
        $mapResult = [];

        foreach ($collection as $item) {
            $mapResult = static::mapFromEntity($item);
        }

        return $mapResult;
    }
}
