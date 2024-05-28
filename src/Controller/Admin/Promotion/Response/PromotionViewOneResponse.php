<?php

namespace App\Controller\Admin\Promotion\Response;

use App\Controller\Admin\Promotion\DTO\Response\PromotionRespDto;
use App\Entity\Ecommerce\Promotion;

class PromotionViewOneResponse
{
    public function makeResponse(Promotion $promotion): PromotionRespDto
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
            ->setUsageWithAnyDiscount($promotion->isUsageWithAnyDiscount());
            // ->setCount($promotion->getCount()); todo нужно7
    }
}