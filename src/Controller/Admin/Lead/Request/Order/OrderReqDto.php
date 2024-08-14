<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\Request\Order;

use App\Controller\Admin\Lead\Request\Order\Product\OrderVariantReqDto;
use App\Controller\Admin\Lead\Request\Order\Promotion\OrderPromotionReqDto;
use App\Controller\Admin\Lead\Request\Order\Shipping\OrderShippingReqDto;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class OrderReqDto
{
    #[Assert\Valid]
    private array $productVariants = [];

    #[Assert\Valid]
    private array $shipping = [];

    #[Assert\Valid]
    private array $promotions = [];

    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private int $totalAmount = 0;

    #[Assert\Callback]
    public function validateExistence(ExecutionContextInterface $context): void
    {
        if (empty($this->promotions) && empty($this->productVariants) && empty($this->shipping)) {
            $context
                ->buildViolation('Order is empty')
                ->addViolation()
            ;
        }
    }

    public function setProductVariants(array $productVariants): self
    {
        $this->productVariants = $productVariants;

        return $this;
    }

    public function getProductVariants(): array
    {
        return $this->productVariants;
    }

    public function addProductVariant(OrderVariantReqDto $productVariant): self
    {
        $this->productVariants[] = $productVariant;

        return $this;
    }

    public function getShipping(): array
    {
        return $this->shipping;
    }

    public function addShipping(OrderShippingReqDto $shipping): self
    {
        $this->shipping[] = $shipping;

        return $this;
    }

    public function getPromotions(): array
    {
        return $this->promotions;
    }

    public function addPromotion(OrderPromotionReqDto $promotion): self
    {
        $this->promotions[] = $promotion;

        return $this;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }
}
