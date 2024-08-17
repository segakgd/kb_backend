<?php

declare(strict_types=1);

namespace App\Controller\Admin\Product\DTO\Response;

use App\Controller\AbstractResponse;
use App\Entity\Ecommerce\ProductVariant;
use Exception;

class ProductVariantResponse extends AbstractResponse
{
    public ?string $article;

    public string $name;

    public ?int $count;

    public array $price;

    public array $images;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof ProductVariant) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->article = $entity->getArticle();
        $response->name = $entity->getName();
        $response->count = $entity->getCount();
        $response->price = $entity->getPrice();
        $response->images = $entity->getImage();

        return $response;
    }
}
