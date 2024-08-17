<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product\DTO\Response;

use App\Controller\AbstractResponse;
use App\Controller\Admin\ProductCategory\Response\ProductCategoryResponse;
use App\Entity\Ecommerce\Product;
use Exception;

class ProductResponse extends AbstractResponse
{
    public int $id;

    public string $name;

    public bool $visible;

    public string $description;

    public array $categories;

    public array $variants;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof Product) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->id = $entity->getId();
        $response->name = $entity->getName();
        $response->visible = $entity->isVisible();
        $response->description = $entity->getDescription();
        $response->categories = ProductCategoryResponse::mapCollection($entity->getCategories());
        $response->variants = ProductVariantResponse::mapCollection($entity->getVariants());

        return $response;
    }
}
