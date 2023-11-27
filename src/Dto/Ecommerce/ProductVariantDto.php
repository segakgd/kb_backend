<?php

namespace App\Dto\Ecommerce;

use DateTimeImmutable;
use DateTimeInterface;

class ProductVariantDto
{
    private ?int $id = null;

    private ?string $name = null;

    private ?string $article = null;

    private array $image = [];

    private array $price = [];

    private ?int $count = null;

    private ?bool $promotionDistributed = null;

    private ?int $percentDiscount = null;

    private ?bool $active = null;

    private ?DateTimeInterface $activeFrom = null;

    private ?DateTimeInterface $activeTo = null;

    private ?DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(?string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getImage(): array
    {
        return $this->image;
    }

    public function setImage(array $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrice(): array
    {
        return $this->price;
    }

    public function setPrice(array $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function isPromotionDistributed(): ?bool
    {
        return $this->promotionDistributed;
    }

    public function setPromotionDistributed(?bool $promotionDistributed): self
    {
        $this->promotionDistributed = $promotionDistributed;

        return $this;
    }

    public function getPercentDiscount(): ?int
    {
        return $this->percentDiscount;
    }

    public function setPercentDiscount(?int $percentDiscount): self
    {
        $this->percentDiscount = $percentDiscount;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getActiveFrom(): ?DateTimeInterface
    {
        return $this->activeFrom;
    }

    public function setActiveFrom(?DateTimeInterface $activeFrom): self
    {
        $this->activeFrom = $activeFrom;

        return $this;
    }

    public function getActiveTo(): ?DateTimeInterface
    {
        return $this->activeTo;
    }

    public function setActiveTo(?DateTimeInterface $activeTo): self
    {
        $this->activeTo = $activeTo;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
