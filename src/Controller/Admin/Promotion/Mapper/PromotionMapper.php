<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion\Mapper;

use App\Controller\Admin\Promotion\Request\PromotionRequest;
use App\Entity\Ecommerce\Promotion;

class PromotionMapper
{
    public static function mapRequestToEntity(PromotionRequest $promotionReqDto): Promotion
    {
        return (new Promotion())
            ->setName($promotionReqDto->getName())
            ->setType($promotionReqDto->getType())
            ->setCode($promotionReqDto->getCode())
            ->setDiscountType($promotionReqDto->getDiscountType())
            ->setAmount($promotionReqDto->getAmount())
            ->setCount($promotionReqDto->getCount())
            ->setActive($promotionReqDto->isActive())
            ->setUsageWithAnyDiscount($promotionReqDto->isUsageWithAnyDiscount())
            ->setActiveFrom($promotionReqDto->getActiveFrom())
            ->setActiveTo($promotionReqDto->getActiveTo());
    }

    public static function mapRequestToExistingEntity(PromotionRequest $promotionReqDto, Promotion $promotion): Promotion
    {
        return $promotion
            ->setName($promotionReqDto->getName())
            ->setType($promotionReqDto->getType())
            ->setCode($promotionReqDto->getCode())
            ->setDiscountType($promotionReqDto->getDiscountType())
            ->setAmount($promotionReqDto->getAmount())
            ->setCount($promotionReqDto->getCount())
            ->setActive($promotionReqDto->isActive())
            ->setUsageWithAnyDiscount($promotionReqDto->isUsageWithAnyDiscount())
            ->setActiveFrom($promotionReqDto->getActiveFrom())
            ->setActiveTo($promotionReqDto->getActiveTo());
    }
}
