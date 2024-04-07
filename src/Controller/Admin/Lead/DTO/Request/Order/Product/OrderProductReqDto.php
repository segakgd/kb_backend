<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\DTO\Request\Order\Product;

class OrderProductReqDto
{
    private array $variants;

    private int $totalAmount = 0;

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function setVariants(array $variants): self
    {
        $this->variants = $variants;

        return $this;
    }

    public function addVariant(OrderVariantReqDto $variant): self
    {
        $this->variants[] = $variant;

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
}
