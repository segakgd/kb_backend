<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion\Response;

use App\Controller\AbstractResponse;
use App\Entity\Ecommerce\Promotion;
use DateTimeInterface;
use Exception;

class PromotionResponse extends AbstractResponse
{
    public string $name;

    public string $discountType;

    public string $type; // todo use enum (values: percent, current)

    public string $code;

    public bool $usageWithAnyDiscount;

    public bool $active;

    public int $amount;

    public string $amountWithFraction;

    public ?DateTimeInterface $activeFrom = null;

    public ?DateTimeInterface $activeTo = null;

    /**
     * @throws Exception
     */
    public static function mapFromEntity(object $entity): static
    {
        if (!$entity instanceof Promotion) {
            throw new Exception('Entity with undefined type.');
        }

        $response = new static();

        $response->name = $entity->getName();
        $response->discountType = $entity->getDiscountType();
        $response->type = $entity->getType();
        $response->code = $entity->getCode();
        $response->usageWithAnyDiscount = $entity->isUsageWithAnyDiscount();
        $response->active = $entity->isActive();
        $response->amount = $entity->getAmount();
        $response->amountWithFraction = $entity->getAmount() ? (string) $entity->getAmount() * 0.01 : '0.0';
        $response->activeFrom = $entity->getActiveFrom();
        $response->activeTo = $entity->getActiveTo();

        return $response;
    }
}
