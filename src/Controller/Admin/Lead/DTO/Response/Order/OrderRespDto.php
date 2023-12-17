<?php

namespace App\Controller\Admin\Lead\DTO\Response\Order;

use App\Controller\Admin\Lead\DTO\Response\Order\Payment\PaymentRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Product\ProductRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Promotion\PromotionRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\Shipping\ShippingRespDto;
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

    public function addProduct(ProductRespDto $product): self
    {
        $this->products[] = $product;

        return $this;
    }

    public function getShipping(): ShippingRespDto
    {
        return $this->shipping;
    }

    public function setShipping(ShippingRespDto $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getPromotions(): array
    {
        return $this->promotions;
    }

    public function addPromotion(PromotionRespDto $promotion): self
    {
        $this->promotions[] = $promotion;

        return $this;
    }

    public function getPayment(): PaymentRespDto
    {
        return $this->payment;
    }

    public function setPayment(PaymentRespDto $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
