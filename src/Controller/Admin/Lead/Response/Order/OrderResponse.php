<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\Response\Order;

use App\Controller\Admin\Lead\Response\Order\Product\ProductResponse;
use App\Controller\Admin\Lead\Response\Order\Promotion\PromotionResponse;
use App\Controller\Admin\Lead\Response\Order\Shipping\ShippingResponse;
use DateTimeImmutable;

class OrderResponse
{
    private array $products = [];

    private array $shipping = [];

    private array $promotions = [];

    private DateTimeImmutable $createdAt;

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function addProduct(ProductResponse $product): self
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * @return ShippingResponse[]
     */
    public function getShipping(): array
    {
        return $this->shipping;
    }

    public function setShipping(array $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function addShipping(ShippingResponse $respDto): self
    {
        $this->shipping[] = $respDto;

        return $this;
    }

    public function getPromotions(): array
    {
        return $this->promotions;
    }

    public function addPromotion(PromotionResponse $promotion): self
    {
        $this->promotions[] = $promotion;

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
