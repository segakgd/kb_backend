<?php

namespace App\Controller\Admin\Lead\DTO\Request\Order;

use App\Controller\Admin\Lead\DTO\Request\Order\Product\OrderProductReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Promotion\OrderPromotionReqDto;
use App\Controller\Admin\Lead\DTO\Request\Order\Shipping\OrderShippingReqDto;

class OrderReqDto
{
    private array $products;

    private OrderShippingReqDto $shipping;

    private array $promotions;


    public function getProducts(): array
    {
        return $this->products;
    }

    public function addProduct(OrderProductReqDto $product): self
    {
        $this->products[] = $product;

        return $this;
    }

    public function getShipping(): OrderShippingReqDto
    {
        return $this->shipping;
    }

    public function setShipping(OrderShippingReqDto $shipping): self
    {
        $this->shipping = $shipping;

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
