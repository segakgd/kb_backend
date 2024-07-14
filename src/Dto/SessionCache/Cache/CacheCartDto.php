<?php

namespace App\Dto\SessionCache\Cache;

use App\Dto\Common\AbstractDto;

class CacheCartDto extends AbstractDto
{
    // todo use CacheContactDto.php
    private array $contacts = [];

    // todo use CacheProductDto.php
    private array $products = [];

    // todo use CacheShippingDto.php
    private array $shipping = [];

    // todo use CachePromotionDto.php
    private array $promotion = [];

    private int $totalAmount = 0;

    private bool $pay = false;

    public function getContacts(): array
    {
        return $this->contacts;
    }

    public function setContacts(array $contacts): static
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $products): static
    {
        $this->products = $products;

        return $this;
    }

    public function addProduct(array $product): static
    {
        $this->products[] = $product;

        return $this;
    }

    public function getShipping(): array
    {
        return $this->shipping;
    }

    public function setShipping(array $shipping): static
    {
        $this->shipping = $shipping;

        return $this;
    }

    public function getPromotion(): array
    {
        return $this->promotion;
    }

    public function setPromotion(array $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function isPay(): bool
    {
        return $this->pay;
    }

    public function setPay(bool $pay): static
    {
        $this->pay = $pay;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $cart = new static();

        $cart->contacts = $data['contacts'] ?? [];
        $cart->products = $data['products'] ?? [];
        $cart->shipping = $data['shipping'] ?? [];
        $cart->promotion = $data['promotion'] ?? [];
        $cart->totalAmount = $data['totalAmount'] ?? 0;
        $cart->pay = $data['pay'] ?? false;

        return $cart;
    }

    public function toArray(): array
    {
        return [
            'contacts' => $this->contacts,
            'products' => $this->products,
            'shipping' => $this->shipping,
            'promotion' => $this->promotion,
            'totalAmount' => $this->totalAmount,
            'pay' => $this->pay,
        ];
    }
}
