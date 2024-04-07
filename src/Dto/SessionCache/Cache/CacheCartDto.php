<?php

namespace App\Dto\SessionCache\Cache;

use App\Dto\Common\AbstractDto;

class CacheCartDto extends AbstractDto
{
    private array $contacts = [];

    private array $products = [];

    private array $shipping = [];

    private array $promotion = [];

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
        $cart = new self();

        $cart->contacts = $data['contacts'] ?? [];
        $cart->products = $data['products'] ?? [];
        $cart->shipping = $data['shipping'] ?? [];
        $cart->promotion = $data['promotion'] ?? [];
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
            'pay' => $this->pay,
        ];
    }
}
