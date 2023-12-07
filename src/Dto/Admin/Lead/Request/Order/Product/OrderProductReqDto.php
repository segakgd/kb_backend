<?php

namespace App\Dto\Admin\Lead\Request\Order\Product;

class OrderProductReqDto
{
    private array $variants;

    private int $totalCount;

    private int $totalAmount;

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function addVariant(OrderVariantReqDto $variant): void
    {
        $this->variants[] = $variant;
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
}
