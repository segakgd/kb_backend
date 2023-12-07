<?php

namespace App\Dto\one;

use DateTimeImmutable;

class OrderRespDto
{
    private array $products;

    private ShippingRespDto $shipping;

    private array $promotions;

    private PaymentRespDto $payment;

    private DateTimeImmutable $createdAt;

    public function getProducts(): array
    {
        return $this->products;
    }

    public function addProduct(ProductRespDto $product): void
    {
        $this->products[] = $product;
    }

    public function getShipping(): ShippingRespDto
    {
        return $this->shipping;
    }

    public function setShipping(ShippingRespDto $shipping): void
    {
        $this->shipping = $shipping;
    }

    public function getPromotions(): array
    {
        return $this->promotions;
    }

    public function addPromotion(PromotionRespDto $promotion): void
    {
        $this->promotions[] = $promotion;
    }

    public function getPayment(): PaymentRespDto
    {
        return $this->payment;
    }

    public function setPayment(PaymentRespDto $payment): void
    {
        $this->payment = $payment;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
