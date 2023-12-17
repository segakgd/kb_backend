<?php

namespace App\Controller\Admin\Promotion\DTO\Response;

class ViewAllPromotionsRespDto
{
    private PromotionRespDto $discounts;

    private PromotionRespDto $promoCodes;

    public function getDiscounts(): PromotionRespDto
    {
        return $this->discounts;
    }

    public function addDiscounts(PromotionRespDto $discount): void
    {
        $this->discounts[] = $discount;
    }

    public function getPromoCodes(): PromotionRespDto
    {
        return $this->promoCodes;
    }

    public function addPromoCodes(PromotionRespDto $promoCode): void
    {
        $this->promoCodes[] = $promoCode;
    }
}
