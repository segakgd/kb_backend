<?php

namespace App\Controller\Admin\Lead\DTO\Request\Order;

use App\Controller\Admin\Lead\DTO\Request\Order\Product\OrderProductReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Promotion\OrderPromotionReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Shipping\OrderShippingReqDto;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class OrderReqDto
{
    #[Assert\Valid]
    private array $products = [];

    #[Assert\Valid]
    private array $shipping = [];

    #[Assert\Valid]
    private array $promotions = [];

    #[Assert\Callback]
    public function validateExistence(ExecutionContextInterface $context): void
    {
        if (empty($this->promotions) && empty($this->products) && empty($this->shipping)) {
            $context
                ->buildViolation('Order is empty')
                ->addViolation()
            ;
        }
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function addProduct(OrderProductReqDto $product): self
    {
        $this->products[] = $product;

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
}
