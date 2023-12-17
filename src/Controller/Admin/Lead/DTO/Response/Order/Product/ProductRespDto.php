<?php

namespace App\Controller\Admin\Lead\DTO\Response\Order\Product;

class ProductRespDto
{
    const TYPE_SERVICE = 'service';

    const TYPE_PRODUCT = 'product';

    private string $name;

    private string $type; // product, service

    private ProductCategoryRespDto $category;

    private ProductVariantRespDto $variant;

    private string $image;

    private ?int $totalCount = null;

    private int $totalAmount = 0;

    private string $totalAmountWF = '0';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCategory(): ProductCategoryRespDto
    {
        return $this->category;
    }

    public function setCategory(ProductCategoryRespDto $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getVariant(): ProductVariantRespDto
    {
        return $this->variant;
    }

    public function setVariant(ProductVariantRespDto $variant): self
    {
        $this->variant = $variant;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setTotalCount(?int $totalCount): self
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): self
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getTotalAmountWF(): string
    {
        return $this->totalAmountWF;
    }

    public function setTotalAmountWF(string $totalAmountWithFraction): self
    {
        $this->totalAmountWF = $totalAmountWithFraction;

        return $this;
    }
}
