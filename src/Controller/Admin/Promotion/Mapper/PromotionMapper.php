<?php

declare(strict_types=1);

namespace App\Controller\Admin\Promotion\Mapper;

use App\Controller\Admin\Promotion\Request\PromotionRequest;
use App\Controller\Admin\Promotion\Response\PromotionResponse;
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

    public static function mapToResponseDto(Promotion $promotion): PromotionResponse
    {
        return (new PromotionResponse())
            ->setName($promotion->getName())
            ->setType($promotion->getType())
            ->setCode($promotion->getCode())
            ->setDiscountType($promotion->getDiscountType())
            ->setAmount($promotion->getAmount())
            ->setIsActive($promotion->isActive())
            ->setActiveFrom($promotion->getActiveFrom())
            ->setActiveTo($promotion->getActiveTo())
            ->setUsageWithAnyDiscount($promotion->isUsageWithAnyDiscount());
    }

    public static function mapArrayToResponseDto(array $promotionCollection): array
    {
        return array_map(function (Promotion $promotion) {
            return self::mapToResponseDto($promotion);
        }, $promotionCollection);
    }
}
