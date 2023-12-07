<?php

namespace App\Dto\Order;

class ProductRespDto
{
    private string $name;

    /** product, service */
    private string $type;

    private ProductCategoryRespDto $category;

    private ProductVariantRespDto $variant;

    private string $image;

    private int $totalCount = 0;

    private int $totalAmount = 0;

    private string $totalAmountWithFraction = '0';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getCategory(): ProductCategoryRespDto
    {
        return $this->category;
    }

    public function setCategory(ProductCategoryRespDto $category): void
    {
        $this->category = $category;
    }

    public function getVariant(): ProductVariantRespDto
    {
        return $this->variant;
    }

    public function setVariant(ProductVariantRespDto $variant): void
    {
        $this->variant = $variant;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function getTotalAmountWithFraction(): string
    {
        return $this->totalAmountWithFraction;
    }

    public function setTotalAmountWithFraction(string $totalAmountWithFraction): void
    {
        $this->totalAmountWithFraction = $totalAmountWithFraction;
    }
}
