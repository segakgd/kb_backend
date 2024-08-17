<?php

declare(strict_types=1);

namespace App\Controller\Admin\Shipping\Response;

use App\Controller\AbstractResponse;
use App\Dto\Ecommerce\Shipping\ShippingPriceDto;
use App\Entity\Ecommerce\Shipping;
use Exception;

class ShippingResponse extends AbstractResponse
{
    public string $name;

    public string $type;

    public string $calculationType;

    public ?ShippingPriceDto $price;

    public ?int $applyFromAmount;

    public string $applyFromAmountWF;

    public ?int $applyToAmount;

    public string $applyToAmountWF;

    public string $description;

    public array $fields;

    public bool $active;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof Shipping) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->name = $entity->getTitle();
        $response->type = $entity->getType();
        $response->applyFromAmount = $entity->getApplyFromAmount();
        $response->applyFromAmountWF = $entity->getApplyFromAmount() ? (string) $entity->getApplyFromAmount() * 0.01 : '0.0';
        $response->active = $entity->isActive();
        $response->applyToAmount = $entity->getApplyToAmount();
        $response->applyToAmountWF = $entity->getApplyToAmount() ? (string) $entity->getApplyToAmount() * 0.01 : '0.0';
        $response->calculationType = $entity->getCalculationType();
        $response->description = $entity->getDescription();
        $response->price = $entity->getPrice();
        $response->fields = $entity->getFields();

        return $response;
    }
}
