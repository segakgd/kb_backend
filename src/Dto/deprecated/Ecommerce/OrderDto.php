<?php

namespace App\Dto\deprecated\Ecommerce;

class OrderDto
{
    private ?int $id = null;

    /** @var array<ProductDto>|null */
    private ?array $products = null;

    private ?ShippingDto $shipping = null;

    /** @var array<PromotionDto>|null */
    private ?array $promotions = null;

    private ?int $totalAmount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getProducts(): ?array
    {
        return $this->products;
    }

    public function setProducts(?array $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function addProduct(?ProductDto $product): self
    {
        $this->products[] = $product;

        return $this;
    }

    public function getShipping(): ?ShippingDto
    {
        return $this->shipping;
    }

    public function setShipping(?ShippingDto $shipping): self
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getPromotions(): ?array
    {
        return $this->promotions;
    }

    public function setPromotions(?array $promotions): self
    {
        $this->promotions = $promotions;

        return $this;
    }

    public function addPromotion(?PromotionDto $promotion): self
    {
        $this->promotions[] = $promotion;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(?int $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }
}
