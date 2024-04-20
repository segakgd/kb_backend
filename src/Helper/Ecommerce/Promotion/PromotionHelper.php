<?php

declare(strict_types=1);

namespace App\Helper\Ecommerce\Promotion;

use App\Controller\Admin\Promotion\DTO\Request\PromotionReqDto;
use App\Controller\Admin\Promotion\DTO\Response\PromotionRespDto;
use App\Entity\Ecommerce\Promotion;

class PromotionHelper
{
    public static function mapRequestToEntity(PromotionReqDto $promotionReqDto): Promotion
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

    public static function mapRequestToExistingEntity(PromotionReqDto $promotionReqDto, Promotion $promotion): Promotion
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

    public static function mapToResponseDto(Promotion $promotion): PromotionRespDto
    {
        return (new PromotionRespDto())
            ->setName($promotion->getName())
            ->setType($promotion->getType())
            ->setCode($promotion->getCode())
            ->setDiscountType($promotion->getDiscountType())
            ->setAmount($promotion->getAmount())
            ->setIsActive($promotion->isActive())
            ->setActiveFrom($promotion->getActiveFrom())
            ->setActiveTo($promotion->getActiveTo())
            ->setUsageWithAnyDiscount($promotion->isUsageWithAnyDiscount())
            ->setCount($promotion->getCount());
    }

    public static function mapArrayToResponseDto(array $promotionCollection): array
    {
        return array_map(function (Promotion $promotion) {
            return self::mapToResponseDto($promotion);
        }, $promotionCollection);
    }
}
