<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead\DTO\Request\Order\Product;

class OrderProductReqDto
{
    private array $variants;

    private int $totalCount; // todo -> не увеерн о нужде этого поля в дто. Оно определяется динамически в результате обработки $variants
//
    private int $totalAmount; // todo -> не уверен о нужден этого поля в дто. Оно определяется динамически в результате обработки $variants

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function addVariant(OrderVariantReqDto $variant): self
    {
        $this->variants[] = $variant;

        return $this;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setTotalCount(int $totalCount): self
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
}
