<?php

namespace App\Controller\Admin\Promotion\DTO\Response;

class ViewAllPromotionsRespDto
{
    private array $discounts;

    private array $promoCodes;

    public function getDiscounts(): array
    {
        return $this->discounts;
    }

    public function addDiscounts(PromotionRespDto $discount): self
    {
        $this->discounts[] = $discount;

        return $this;
    }

    public function getPromoCodes(): array
    {
        return $this->promoCodes;
    }

    public function addPromoCodes(PromotionRespDto $promoCode): self
    {
        $this->promoCodes[] = $promoCode;

        return $this;
    }
}
