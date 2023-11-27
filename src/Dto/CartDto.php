<?php

namespace App\Dto;

use App\Dto\Ecommerce\ProductDto;
use DateTimeImmutable;

class CartDto
{
    private ?int $id = null;

    private array $products = [];

    private ?int $totalAmount = null;

    private ?int $visitorId = null;

    private ?string $status = null;

    private ?DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        if ($this->createdAt === null){
            $this->createdAt = new DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $products): self
    {
        $this->products = $products;

        return $this;
    }
    public function addProduct(ProductDto $product): self
    {
        $this->products[] = $product;

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

    public function getVisitorId(): ?int
    {
        return $this->visitorId;
    }

    public function setVisitorId(?int $visitorId): self
    {
        $this->visitorId = $visitorId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
