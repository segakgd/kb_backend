<?php

namespace App\Dto\Admin\Lead\Request\Order;

use App\Dto\Admin\Lead\Request\Order\Product\OrderProductReqDto;
use App\Dto\Admin\Lead\Request\Order\Promotion\OrderPromotionReqDto;
use App\Dto\Admin\Lead\Request\Order\Shipping\OrderShippingReqDto;

class OrderReqDto
{
    private array $products;

    private OrderShippingReqDto $shipping;

    private array $promotions;


    public function getProducts(): array
    {
        return $this->products;
    }

    public function addProduct(OrderProductReqDto $product): void
    {
        $this->products[] = $product;
    }

    public function getShipping(): OrderShippingReqDto
    {
        return $this->shipping;
    }

    public function setShipping(OrderShippingReqDto $shipping): void
    {
        $this->shipping = $shipping;
    }

    public function getPromotions(): array
    {
        return $this->promotions;
    }

    public function addPromotion(OrderPromotionReqDto $promotion): void
    {
        $this->promotions[] = $promotion;
    }
}
